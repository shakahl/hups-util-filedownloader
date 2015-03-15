<?php

namespace Hups\Util;

/**
 * File downloader utility class
 * - supports large file downloads
 * - supports resuming downloads
 * @category  Util
 * @package   Hups\Util\FileDownloader
 * @author    Szélpál Soma <szelpalsoma+hups@gmail.com>
 * @license   MIT Open Source License http://opensource.org/osi3.0/licenses/mit-license.php
 * @version   @package-version@
 */
class FileDownloader
{
    /**
     * Sends the file to the browser for download
     * 
     * @param  string $location         Path to the file
     * @param  string|null $filename    Filename to download as
     * @param  string|null $mime        Mime type. Set to null for autodetect.
     * @param  int $speed               Download speed
     * @param  string $disposition      Content disposition. attachment or inline.
     * @return bool                     Return status.
     */
    public static function download($location, $filename = null, $mime = 'application/octet-stream', $speed = 1024, $disposition = 'attachment')
    {
        if (connection_status() != 0) return(false);

        if(!is_file($location))
        {
            http_response_code(404);
            exit();
        }

        if (empty($mime))
        {
            $mime = static::getFileMime($location);
        }

        set_time_limit(0);

        while (ob_get_level() > 0)
        {
            ob_end_clean();
        }

        if (!$filename)
        {
            $filename = pathinfo($location, PATHINFO_BASENAME);
        }

        $time = date('r', filemtime($location));
        $size = filesize($location);
        $speed = ($speed === null) ? 1024 : intval($speed) * 1024;
        $begin = 0;
        $end = $size;
        $status = 200;

        if(isset($_SERVER['HTTP_RANGE']))
        {
            if(preg_match('/bytes=\h*(\d+)-(\d*)[\D.*]?/i', $_SERVER['HTTP_RANGE'], $matches))
            {
                $begin = intval($matches[0]);

                if(!empty($matches[1]))
                {
                    $end = intval($matches[1]);
                }

                $status = 206;
            }
        }

        http_response_code($status);

        header('Expires: 0');
        header("Content-Type: $mime");
        header('Pragma: public');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Accept-Ranges: bytes');
        header('Content-Length: '.($end - $begin));
        header("Content-Range: bytes $begin-" . ($end - 1) . "/$size");
        header("Content-Disposition: " . $disposition . "; filename=$filename");
        header("Content-Transfer-Encoding: binary");
        header("Last-Modified: $time");
        header('Connection: close');

        for ($offset = $begin; $offset <= $end; $offset += $speed)
        {
            if (connection_status() || connection_aborted())
            {
                exit(1);
            }

            echo file_get_contents($location, false, null, $offset, $speed);

            while (ob_get_level() > 0)
            {
                ob_end_flush();
            }

            flush();
            //ob_flush();
            //ob_implicit_flush();

            usleep(intval(floatval($time) * 1000000));
        }

        return ((connection_status()==0) and !connection_aborted());
    }

    /**
     * Get file's mime type
     * @param  string $filename
     * @param  string $default  Default value
     * @return string
     */
    public static function getFileMime($filename, $default = 'application/octet-stream')
    {
        if(function_exists('mime_content_type'))
        {
            return mime_content_type($filename);
        }

        $mime_types = array(

            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',

            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',

            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',

            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',

            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',

            // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',

            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );

        $ext = substr(strrchr($filename, "."), 1);

        if (array_key_exists($ext, $mime_types))
        {
            return $mime_types[$ext];
        }
        elseif (function_exists('finfo_open'))
        {
            $finfo = finfo_open(FILEINFO_MIME);
            $mimetype = finfo_file($finfo, $filename);
            finfo_close($finfo);
            return $mimetype;
        }        

        return $default;
    }
}

