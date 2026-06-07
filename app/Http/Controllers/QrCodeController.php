<?php

namespace App\Http\Controllers;

use App\Features\QrCodeService;
use App\Models\Appraise;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class QrCodeController extends Controller
{
    /**
     * 下载单个评价的二维码图片
     */
    public function download(Appraise $appraise): Response
    {
        $pngData = QrCodeService::generateAppraiseQrImage($appraise);
        $filename = QrCodeService::getSafeFilename($appraise);

        return response($pngData)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="'.$filename.'"')
            ->header('Content-Length', strlen($pngData));
    }

    /**
     * 批量下载评价二维码（ZIP）
     */
    public function batchDownload(Request $request): StreamedResponse
    {
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            abort(400, '请选择要下载二维码的评价');
        }

        $appraises = Appraise::whereIn('id', $ids)->get();

        if ($appraises->isEmpty()) {
            abort(404, '未找到选中的评价');
        }

        $zipPath = QrCodeService::generateAppraiseQrZip($appraises);
        $zipFilename = '种质评价二维码_'.now()->format('YmdHis').'.zip';

        return response()->streamDownload(function () use ($zipPath) {
            readfile($zipPath);
            @unlink($zipPath);
        }, $zipFilename, [
            'Content-Type' => 'application/zip',
        ]);
    }
}
