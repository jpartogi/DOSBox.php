<?php

namespace DOSBox\Interfaces;

use DOSBox\Filesystem\Directory;

interface IDrive {
    /**Sets drive label, as used in command VOL and LABEL
     * @param newLabel new label. Any text, may contain spaces
     */
    public function setLabel($newLabel);


    /**Sets drive label, as used in command VOL and LABEL
     * @return current drive label
     */
    public function getLabel();

    /**E.g. "C:"
     */
    public function getDriveLetter();

    /**Current Directory + ">"
     */
    public function getPrompt();

    /**Gets the drive's root directory.
     */
    public function getRootDirectory();

    /**Gets the currently active directory on this drive.
     */
    public function getCurrentDirectory();

    /**Changes the currently active directory on this drive.
     * @param newCurrentDirectory Must be a directory within the drive's directory structure!
     * @return false if current directory could not be changed
     */
    public function changeCurrentDirectory(Directory $newCurrentDirectory);

    /**Returns the object of a given path name.
     *
     * Example:
     * getItemFromPath("C:\\temp\\aFile.txt");
     * Returns the FileSystemItem-object which abstracts aFile.txt in the temp directory.
     *
     * Remarks:
     * - Always use "\\" for backslashes since the backslash is used as escape character for Java strings.
     * - This operation works for relative paths (temp\\aFile.txt) too. The lookup starts at the current directory.
     * - This operation works for forward slashes '/' too.
     * - ".." and "." are supported too.
     *
     * @param givenItemPath Path for which the item shall be returned.
     * @return FileSystemObject or null if no path found.
     */
    public function getItemFromPath($givenItemPath);

    /**Stores the current directory structure persistently.
     */
    public function save();

    /**
     * Creates a directory structure from the stored structure. The current directory structure is deleted.
     */
    public function restore();

    /**Builds up a directory structure from the given path on a real drive.
     * Sub-directories become directories and sub-directories
     * Files in that directory and the sub-directories become files, content is set to
     * full path, filename and size of that file.
     *
     * Example:
     * C:\temp
     * +-- MyFile1.txt (size 112000 Bytes)
     * +-- MyFile2.txt (50000)
     * +-- SubDir1 (Dir)
     * ....+-- AnExecutable.exe (1234000)
     * ....+-- ConfigFiles (Dir)
     *
     * Results in
     * - All files and sub-directories of the root directory deleted
     * - Current directory set to root directory
     * - File MyFile1.txt added to root directory with content "C:\temp\MyFile1.txt, size 112000 Bytes"
     * - File MyFile2.txt added to root directory with content "C:\temp\MyFile2.txt, size 50000 Bytes"
     * - Directory SubDir1 added to root directory
     * - File AnExecutable.exe added to SubDir1 with content "C:\temp\SubDir1\AnExecutable.exe, size 1234000 Bytes"
     * - Directory ConfigFiles added to SubDir1
     * @param realPath The path to a real directory on any memory device.
     */
    public function createFromRealDirectory($realPath);
}