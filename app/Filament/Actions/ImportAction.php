<?php

namespace App\Filament\Actions;

use Filament\Actions\ImportAction as FilamentImportAction;

class ImportAction extends FilamentImportAction
{
    /**
     * 检测 CSV 文件编码
     *
     * 修复 Filament 默认检测顺序导致 GBK 编码的 CSV 被误判为 UTF-8 的问题：
     * 1. 先检查 UTF-8 BOM，存在则直接返回 UTF-8（最可靠）
     * 2. GB18030 排在 UTF-8 前面，避免 GBK 字节序列被 mb_check_encoding 误判为 UTF-8
     */
    protected function detectCsvEncoding(mixed $resource): ?string
    {
        rewind($resource);

        $lineCount = 0;
        $contentSample = '';

        while ((! feof($resource)) && ($lineCount < 20)) {
            $line = fgets($resource);

            if ($line === false) {
                break;
            }

            $contentSample .= $line;
            $lineCount++;
        }

        // 1. 检查 UTF-8 BOM — 存在则直接确认 UTF-8
        if (str_starts_with($contentSample, "\xEF\xBB\xBF")) {
            return 'UTF-8';
        }

        // 2. 去除可能存在的 UTF-16 BOM 残留后，按优先级检测
        // GB18030（GBK 超集）排在 UTF-8 前面，
        // 防止 Excel 在中文 Windows 保存的 GBK 编码被 mb_check_encoding 误判为 UTF-8
        $encodings = [
            'GB18030',
            'UTF-8',
            'SJIS-win',
            'EUC-KR',
            'ISO-8859-1',
            'Windows-1251',
            'Windows-1252',
            'EUC-JP',
        ];

        foreach ($encodings as $encoding) {
            if (! mb_check_encoding($contentSample, $encoding)) {
                continue;
            }

            return $encoding;
        }

        return null;
    }
}
