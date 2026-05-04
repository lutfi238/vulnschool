<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * Controller: Admin/Logs
 * Viewer log aplikasi
 * 
 * VULN-IDOR-007: Path Traversal
 */
class Logs extends BaseController
{
    public function index()
    {
        $logPath = WRITEPATH . 'logs/';
        $logs = [];
        
        if (is_dir($logPath)) {
            $files = scandir($logPath);
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..' && pathinfo($file, PATHINFO_EXTENSION) === 'log') {
                    $logs[] = $file;
                }
            }
        }

        // VULN-IDOR-007: Path Traversal
        // Input parameter 'file' digunakan langsung untuk membaca file tanpa sanitasi absolut path
        $selectedFile = $this->request->getGet('file');
        $logContent = '';
        
        if ($selectedFile) {
            $filePath = WRITEPATH . 'logs/' . $selectedFile;
            if (file_exists($filePath)) {
                $logContent = file_get_contents($filePath);
            } else {
                $logContent = "File not found: " . $filePath;
            }
        }

        return view('admin/logs', [
            'title'        => 'System Logs',
            'logs'         => $logs,
            'selectedFile' => $selectedFile,
            'logContent'   => $logContent
        ]);
    }
}
