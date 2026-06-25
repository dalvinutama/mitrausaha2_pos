<?php
// Check for log/activity tables
$tables = Schema::getConnection()->getDoctrineSchemaManager()->listTableNames();
foreach ($tables as $t) {
    if (str_contains($t, 'log') || str_contains($t, 'activ') || str_contains($t, 'audit')) {
        echo "Found: $t\n";
    }
}

// Check if transactions table has any logging
echo "\n--- All tables ---\n";
foreach ($tables as $t) {
    echo "$t\n";
}
