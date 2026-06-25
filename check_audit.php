<?php
echo DB::table('audit_logs')->count() . " total audit logs\n\n";
echo "Last 5:\n";
$logs = DB::table('audit_logs')->orderBy('id', 'desc')->limit(5)->get();
foreach ($logs as $l) {
    echo "[$l->module] $l->action — $l->description\n";
}
