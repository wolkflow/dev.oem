<?php
/**
Содержимое файла .git/hooks/post-merge

-----------------------------------------------------------
#!/bin/sh
/usr/bin/env php .post-merge.php
-----------------------------------------------------------

Не забудьте выполнить chmod +x .git/hooks/post-merge
*/

include __DIR__ . '/local/modules/wolk.core/lib/system/shell.php';

use Wolk\Core\System\Shell;

// Можно выполнять только из командной строки
Shell::shellonly();

$logfile  = '../post-merge.log';

$changed_files  = shell_exec("/usr/bin/env git diff-tree -r --name-only --no-commit-id ORIG_HEAD HEAD");
$log_message    = shell_exec('/usr/bin/env git log -5');

// Данные.
$log_message = explode('commit', $log_message);

// Первый элемент пустой
unset($log_message[0]);
$log_message = array_map('trim', $log_message);

$last_commits = [];
foreach ($log_message as $message) {
    $commit = [];
    $message = explode(PHP_EOL, $message);

    $commit['id'] = trim($message[0]);
    unset($message[0]);

    // tags
    $is_tags = true;
    foreach ($message as $line) {
        $line = trim($line);
        if (!$is_tags) {
            $commit['comment'] .= $line;
            continue;
        }
        $key_value = explode(':', $line);
        if (empty($key_value[0])) {
            $commit['comment'] = $line;
            $is_tags = false;
            continue;
        }
        $key = trim($key_value[0]);
        $value = trim($key_value[1]);

        $commit['is_merge'] = ($key == 'Merge');

        $commit[$key] = $value;
    }
    $last_commits[] = $commit;
}


// find real comment
$comment = [];
foreach ($last_commits as $commit) {
    if ($commit['is_merge']) {
        continue;
    }
    $comment []= $commit['Author'] . ': ' . $commit['comment'];
	
    $author    = $commit['Author'];
    $commit_id = $commit['id'];
}
$comment = str_replace('"', "'" . implode(PHP_EOL, $comment));


// Запуск миграционных файлов.
$changed_files = explode(PHP_EOL, $changed_files);

$msg = '------------------' . PHP_EOL . 'Запуск миграций. ' . PHP_EOL;

echo $msg;

file_put_contents($logfile, PHP_EOL . $msg, FILE_APPEND);


$count = 0;
foreach ($changed_files as $file) {
    /*
     * Запускаем миграции.
	 */
    if (strpos($file, 'migrations/') === 0 && pathinfo($file, PATHINFO_EXTENSION) == 'php') {
		$count++;
		
		$msg = date('d/m/Y H:i:s') . ': запуск миграции ' . $file . PHP_EOL;
		
		echo $msg;
		
		file_put_contents($logfile, $msg, FILE_APPEND);

		$abs_file = dirname(__FILE__) . '/' . $file;
		
		shell_exec("/opt/plesk/php/5.6/bin/php-cgi -f $abs_file >> $logfile");
    }
}

$msg = PHP_EOL . 'Выполнено миграций: ' . $count . PHP_EOL;

echo $msg;

file_put_contents($logfile, PHP_EOL . $msg, FILE_APPEND);

if ($count > 0) {
    echo 'Лог миграций - ', $logfile, PHP_EOL;
}

