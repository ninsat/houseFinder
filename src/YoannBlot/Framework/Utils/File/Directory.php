<?php
declare(strict_types=1);

namespace YoannBlot\Framework\Utils\File;

/**
 * Class Directory.
 * Tools for directories.
 *
 * @package YoannBlot\Framework\Utils\File
 * @author  Yoann Blot
 */
abstract class Directory {

    /**
     * Create a directory and its sub directories.
     *
     * @param string $sDirectoryPath directory.
     *
     * @return boolean true if success, false otherwise.
     */
    public static function create (string $sDirectoryPath) : bool {
        $bSuccess = false;
        if (is_dir($sDirectoryPath)) {
            $bSuccess = true;
        } else {
            if ('' !== $sDirectoryPath) {
                $bSuccess = @mkdir($sDirectoryPath, 0705, true);
            }
        }

        return $bSuccess;
    }

    /**
     * Delete a file or recursively delete a directory.
     *
     * @param string $sDirectory directory path.
     *
     * @return boolean success.
     */
    public static function delete (string $sDirectory) : bool {
        if (!file_exists($sDirectory)) {
            $bSuccess = true;
        } else {
            if (is_dir($sDirectory)) {
                foreach (scandir($sDirectory) as $sFile) {
                    if ('.' !== $sFile && '..' !== $sFile) {
                        self::delete($sDirectory . DIRECTORY_SEPARATOR . $sFile);
                    }
                }
                $bSuccess = @rmdir($sDirectory);
            } else {
                $bSuccess = @unlink($sDirectory);
            }
        }

        return $bSuccess;
    }
}
