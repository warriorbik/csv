<?php

namespace Wilgucki\Csv;

/**
 * Class for writing CSV files
 *
 * @package wilgucki/csv
 * @author Maciej Wilgucki <mwilgucki@gmail.com>
 * @license https://github.com/wilgucki/dbrepository/blob/master/LICENSE
 * @link https://github.com/wilgucki/csv
 */
class Writer
{
    protected $handle = null;
    protected $delimiter = ',';
    protected $enclosure = '"';
    protected $escape = '\\';

    /**
     * Open CSV file for writing.
     *
     * @param string $file File name for writing CSV data. If not provided memory will be used as CSV file.
     * @param null   $delimiter @see http://php.net/manual/en/function.fputcsv.php
     * @param null   $enclosure @see http://php.net/manual/en/function.fputcsv.php
     * @param null   $escape @see http://php.net/manual/en/function.fputcsv.php
     *
     * @return $this
     */
    public function create($file = 'php://memory', $delimiter = null, $enclosure = null, $escape = null)
    {
        if ($delimiter !== null) {
            $this->delimiter = $delimiter;
        }

        if ($enclosure !== null) {
            $this->enclosure = $enclosure;
        }

        if ($escape !== null) {
            $this->escape = $escape;
        }

        $this->handle = fopen($file, 'w+');
        return $this;
    }

    /**
     * Close file pointer
     */
    public function close()
    {
        fclose($this->handle);
    }

    /**
     * Write line to CSV file.
     *
     * @param array $row
     */
    public function writeLine(array $row)
    {
        $this->write($row);
    }

    /**
     * Write all line to CSV file at once
     *
     * @param array $data
     */
    public function writeAll(array $data)
    {
        foreach ($data as $row) {
            $this->writeLine($row);
        }
    }

    /**
     * Output all written data as string.
     *
     * @return string
     */
    public function flush()
    {
        rewind($this->handle);
        $out = stream_get_contents($this->handle);
        fseek($this->handle, 0, SEEK_END);
        return $out;
    }

    /**
     * Wrapper for fputcsv function
     *
     * @param array $row
     */
    private function write(array $row)
    {
        fputcsv($this->handle, $row, $this->delimiter, $this->enclosure, $this->escape);
    }
}