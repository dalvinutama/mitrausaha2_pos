<?php
$enPath = __DIR__.'/lang/en.json';
$idPath = __DIR__.'/lang/id.json';

$en = json_decode(file_get_contents($enPath), true) ?? [];
$id = json_decode(file_get_contents($idPath), true) ?? [];

$newKeysEn = [
    'system' => 'System',
    'security' => 'Security',
    'audit_trail' => 'Audit Trail',
    'system_audit_log' => 'System Audit Log',
    'audit_log_subtitle' => 'Monitoring user activities, change history, and application data integrity.',
    'audit_log_desc' => 'Activity logs are system-protected and cannot be deleted or manipulated to maintain data integrity.',
    'search_activity_actor' => 'Search activity or actor name...',
    'all_modules' => 'All Modules',
    'apply_filter' => 'Apply',
    'reset' => 'Reset',
    'date_time' => 'Date & Time',
    'actor_user' => 'Actor / User',
    'module' => 'Module',
    'action' => 'Action',
    'short_description' => 'Short Description',
    'details' => 'Details',
    'no_activity_history' => 'No activity history yet.',
    'close_details' => 'Close Details',
    'performed_on' => 'Performed on:',
    'by' => 'by',
    'old_data' => 'Old Data',
    'no_old_data' => 'No old data',
    'new_data' => 'New Data',
    'no_new_data' => 'No new data',
    'view_data_comparison' => 'View Data Comparison'
];

$newKeysId = [
    'system' => 'Sistem',
    'security' => 'Keamanan',
    'audit_trail' => 'Audit Trail',
    'system_audit_log' => 'System Audit Log',
    'audit_log_subtitle' => 'Pemantauan aktivitas pengguna, riwayat perubahan, dan integritas data aplikasi.',
    'audit_log_desc' => 'Log aktivitas dilindungi secara sistem dan tidak dapat dihapus atau dimanipulasi demi menjaga integritas data.',
    'search_activity_actor' => 'Cari aktivitas atau nama aktor...',
    'all_modules' => 'Semua Modul',
    'apply_filter' => 'Terapkan',
    'reset' => 'Reset',
    'date_time' => 'Tgl & Waktu',
    'actor_user' => 'Aktor / Pengguna',
    'module' => 'Modul',
    'action' => 'Aksi',
    'short_description' => 'Deskripsi Singkat',
    'details' => 'Detail',
    'no_activity_history' => 'Belum ada riwayat aktivitas.',
    'close_details' => 'Tutup Detail',
    'performed_on' => 'Dilakukan pada:',
    'by' => 'oleh',
    'old_data' => 'Data Lama',
    'no_old_data' => 'Tidak ada data lama',
    'new_data' => 'Data Baru',
    'no_new_data' => 'Tidak ada data baru',
    'view_data_comparison' => 'Lihat Perbandingan Data'
];

$en = array_merge($en, $newKeysEn);
$id = array_merge($id, $newKeysId);

file_put_contents($enPath, json_encode($en, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
file_put_contents($idPath, json_encode($id, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo "Lang updated.\n";
