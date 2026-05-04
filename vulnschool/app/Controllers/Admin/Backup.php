<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * Controller: Admin/Backup
 * Fitur Backup Database (Recon Target)
 */
class Backup extends BaseController
{
    public function index()
    {
        // Cari file backup yang ada di public/backup
        $backupDir = FCPATH . 'backup/';
        $backups = [];
        
        if (is_dir($backupDir)) {
            $files = scandir($backupDir);
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..' && pathinfo($file, PATHINFO_EXTENSION) === 'sql') {
                    $backups[] = [
                        'name' => $file,
                        'size' => round(filesize($backupDir . $file) / 1024, 2) . ' KB',
                        'time' => date('Y-m-d H:i:s', filemtime($backupDir . $file)),
                        'url'  => '/backup/' . $file
                    ];
                }
            }
        }

        return view('admin/backup/index', [
            'title'   => 'Database Backup',
            'backups' => $backups
        ]);
    }

    public function generate()
    {
        // Simulasi pembuatan backup
        $backupDir = FCPATH . 'backup/';
        if (!is_dir($backupDir)) {
            mkdir($backupDir, 0777, true);
        }

        $filename = 'db_' . date('Ymd_His') . '.sql';
        $filepath = $backupDir . $filename;

        // Tulis dummy content
        $content = "-- Backup Database VulnSchool\n";
        $content .= "-- Generated at: " . date('Y-m-d H:i:s') . "\n";
        $content .= "CREATE TABLE dummy_table (id INT);";
        
        file_put_contents($filepath, $content);

        return redirect()->to('/admin/backup')->with('success', 'Backup berhasil digenerate: ' . $filename);
    }
}
