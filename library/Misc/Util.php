<?php

class Misc_Util {

    public static function getHumanReadableBytes($bytes) {
        // http://us3.php.net/manual/en/function.filesize.php#100097
        if ($bytes < 1024) return $bytes.' B';
        elseif ($bytes < 1048576) return round($bytes / 1024, 2).' KB';
        elseif ($bytes < 1073741824) return round($bytes / 1048576, 2).' MB';
        elseif ($bytes < 1099511627776) return round($bytes / 1073741824, 2).' GB';
        else return round($bytes / 1099511627776, 2).' TB';
    }

    public static function pluralize($count, $itemName) {
        return $count.' '.$itemName.($count != 1 ? 's' : '');
    }

    public static function sendEmail($to, $subject, $body, $smtpHost) {
        $from = 'novarad@'.`hostname`;
        // developed for msmtp, may not work with standard sendmail
        if (!mail($to, $subject, $body, 'From: '.$from, '--host='.$smtpHost.' --from '.$from.' --timeout=10')) {
            throw new RuntimeException("sending email failed");
        }
    }

    public static function readErrorLogFile($path) {
        return preg_replace("|\\n{2,}|", "\n", file_get_contents($path));
    }

}
