<?php

namespace App\Features;

use App\Models\Appraise;
use chillerlan\QRCode\Common\EccLevel;
use chillerlan\QRCode\Output\QROutputInterface;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\File;
use Wsmallnews\Cms\Support\Utils as CmsUtils;
use ZipArchive;

class QrCodeService
{
    /**
     * 生成前端评价详情页 URL（二维码扫描专用）
     */
    public static function getAppraiseUrl(Appraise $appraise): string
    {
        $token = self::encodeToken($appraise->resource_no, $appraise->germplasm_no);

        return CmsUtils::route('appraises.qrcode', [
            'token' => $token,
            'tenant' => $appraise->team,
        ]);
    }

    /**
     * 编码：resource_no + germplasm_no → URL-safe token
     */
    public static function encodeToken(string $resourceNo, string $germplasmNo): string
    {
        $raw = $resourceNo . '|' . $germplasmNo;

        return rtrim(strtr(base64_encode($raw), '+/', '-_'), '=');
    }

    /**
     * 解码：token → ['resource_no' => ..., 'germplasm_no' => ...]
     *
     * @return array{resource_no: string, germplasm_no: string}
     */
    public static function decodeToken(string $token): array
    {
        $raw = base64_decode(strtr($token, '-_', '+/'));
        [$resourceNo, $germplasmNo] = explode('|', $raw, 2);

        return [
            'resource_no' => $resourceNo,
            'germplasm_no' => $germplasmNo,
        ];
    }

    /**
     * 生成纯 QR SVG（用于 infolist 小尺寸展示）
     */
    public static function getAppraiseQrSvg(Appraise $appraise): string
    {
        $options = new QROptions([
            'outputType' => QROutputInterface::MARKUP_SVG,
            'eccLevel' => EccLevel::L,
            'scale' => 8,
            'imageBase64' => false,
            'svgConnects' => true,
            'drawCircularModules' => false,
            'quietzoneSize' => 2,
        ]);

        $qrCode = new QRCode($options);

        return $qrCode->render(self::getAppraiseUrl($appraise));
    }

    /**
     * 生成组合图片（QR + 编号 + 种质名称），返回 PNG 二进制
     */
    public static function generateAppraiseQrImage(Appraise $appraise): string
    {
        $qrSize = 328;
        $padding = 10;
        $textAreaHeight = 100;
        $totalWidth = $qrSize + $padding * 2;
        $totalHeight = $qrSize + $padding * 2 + $textAreaHeight;

        // 创建画布
        $image = imagecreatetruecolor($totalWidth, $totalHeight);
        $white = imagecolorallocate($image, 255, 255, 255);
        $black = imagecolorallocate($image, 0, 0, 0);
        $gray = imagecolorallocate($image, 100, 100, 100);

        // 白色背景
        imagefill($image, 0, 0, $white);

        // 生成 QR PNG
        $qrPng = self::generateQrPng(self::getAppraiseUrl($appraise), $qrSize);
        $qrImage = imagecreatefromstring($qrPng);

        // 将 QR 码粘贴到画布
        imagecopy($image, $qrImage, $padding, $padding, 0, 0, $qrSize, $qrSize);
        imagedestroy($qrImage);

        // 绘制文字
        $fontPath = self::getFontPath();
        $fontSize = 12;
        $lineHeight = 26;
        $textX = $padding + 10;
        $textY = $qrSize + $padding + 25;

        // 全国统一编号
        $resourceNo = '全国统一编号：' . ($appraise->resource_no ?? '-');
        if ($fontPath) {
            imagettftext($image, $fontSize, 0, $textX, $textY, $black, $fontPath, $resourceNo);
        } else {
            imagestring($image, 5, $textX, $textY - 12, $resourceNo, $black);
        }

        // 种质圃编号
        $textY += $lineHeight;
        $germplasmNo = '种质圃编号：' . ($appraise->germplasm_no ?? '-');
        if ($fontPath) {
            imagettftext($image, $fontSize, 0, $textX, $textY, $black, $fontPath, $germplasmNo);
        } else {
            imagestring($image, 5, $textX, $textY - 12, $germplasmNo, $black);
        }

        // 种质名称
        $textY += $lineHeight;
        $name = '种质名称：' . ($appraise->name ?? '-');
        if ($fontPath) {
            imagettftext($image, $fontSize, 0, $textX, $textY, $black, $fontPath, $name);
        } else {
            imagestring($image, 5, $textX, $textY - 12, $name, $black);
        }

        // 输出 PNG
        ob_start();
        imagepng($image);
        $pngData = ob_get_clean();
        imagedestroy($image);

        return $pngData;
    }

    /**
     * 批量生成 zip，返回临时文件路径
     */
    public static function generateAppraiseQrZip(Collection $appraises): string
    {
        $tempPath = storage_path('app/temp/appraise-qrcodes-' . time() . '.zip');
        File::ensureDirectoryExists(dirname($tempPath));

        $zip = new ZipArchive;
        if ($zip->open($tempPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new \RuntimeException('无法创建 ZIP 文件');
        }

        foreach ($appraises as $index => $appraise) {
            $pngData = self::generateAppraiseQrImage($appraise);

            // 文件名：序号_资源编号_种质圃编号.png
            $resourceNo = preg_replace('/[^a-zA-Z0-9\-_]/', '_', $appraise->resource_no ?? 'unknown');
            $germplasmNo = preg_replace('/[^a-zA-Z0-9\-_]/', '_', $appraise->germplasm_no ?? 'unknown');
            $name = preg_replace('/[^a-zA-Z0-9\-_\x{4e00}-\x{9fa5}]/u', '_', $appraise->name ?? '');
            $name = mb_substr($name, 0, 30); // 限制文件名长度
            $filename = ($index + 1) . '_' . $resourceNo . '_' . $germplasmNo . '_' . $name . '.png';

            $zip->addFromString($filename, $pngData);
        }

        $zip->close();

        return $tempPath;
    }

    /**
     * 获取安全的文件名（用于下载）
     */
    public static function getSafeFilename(Appraise $appraise): string
    {
        $resourceNo = preg_replace('/[^a-zA-Z0-9\-_]/', '_', $appraise->resource_no ?? 'unknown');
        $germplasmNo = preg_replace('/[^a-zA-Z0-9\-_]/', '_', $appraise->germplasm_no ?? 'unknown');

        return $resourceNo . '_' . $germplasmNo . '.png';
    }

    /**
     * 生成 QR 码 PNG
     */
    private static function generateQrPng(string $data, int $size): string
    {
        $options = new QROptions([
            'outputType' => QROutputInterface::GDIMAGE_PNG,
            'eccLevel' => EccLevel::L,
            'scale' => 8,
            'imageBase64' => false,
            'drawCircularModules' => false,
            'quietzoneSize' => 2,
        ]);

        $qrCode = new QRCode($options);

        return $qrCode->render($data);
    }

    /**
     * 获取中文字体路径，优先使用系统字体
     */
    private static function getFontPath(): ?string
    {
        // Windows 系统字体
        $windowsFont = 'C:/Windows/Fonts/msyh.ttc';
        if (file_exists($windowsFont)) {
            return $windowsFont;
        }

        // Linux 常见中文字体
        $linuxFonts = [
            '/usr/share/fonts/truetype/wqy/wqy-microhei.ttc',
            '/usr/share/fonts/truetype/noto/NotoSansCJK-Regular.ttc',
            '/usr/share/fonts/opentype/noto/NotoSansCJK-Regular.ttc',
        ];

        foreach ($linuxFonts as $font) {
            if (file_exists($font)) {
                return $font;
            }
        }

        return null;
    }
}
