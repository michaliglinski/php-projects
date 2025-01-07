<?php namespace My_Class\Db;

/**
 * Project Name: SQL Query Builder Random Data File
 * File: class.test.php
 * Description: Utility class for generating random test data, such as names, words, and dates.
 * Author: Michał Igliński (mtur.reklama@gmail.com)
 * Created: 2025-01-07
 * Updated: 2025-01-07
 * License: MIT
 *
 * The MIT License (MIT)
 *
 * Copyright (c) 2025 Michał Igliński (mtur.reklama@gmail.com)
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

class Test {

    /**
     * Generates a random lowercase word of a given length.
     *
     * @param int $length The length of the word to generate.
     * @return string The randomly generated word. Returns an empty string if length <= 0.
     */
    static public function generateRandomWord($length) {
        if ($length <= 0) return '';
        $characters = 'abcdefghijklmnopqrstuvwxyz';

        $randomWord = '';
        for ($i = 0; $i < $length; $i++) {
            $randomWord .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $randomWord;
    }

    ////////////////////////////////////////////
    ////////////////////////////////////////////

    /**
     * Generates a random capitalized name of a given length.
     *
     * @param int $length The length of the name to generate.
     * @return string The randomly generated name, with the first letter capitalized.
     */
    static public function generateRandomName($length) {
        return ucfirst(self::generateRandomWord($length));
    }

    ////////////////////////////////////////////
    ////////////////////////////////////////////

    /**
     * Generates a random date between two specified dates.
     *
     * @param string $startDate The start date in "Y-m-d" format.
     * @param string $endDate The end date in "Y-m-d" format.
     * @return string The randomly generated date in "Y-m-d" format,
     *                or an error message if the input dates are invalid.
     */
    static public function generateRandomDate($startDate, $endDate) {
        $startTimestamp = strtotime($startDate);
        $endTimestamp = strtotime($endDate);

        if ($startTimestamp === false || $endTimestamp === false || $startTimestamp > $endTimestamp) {
            return "Wrong time periods";
        }

        $randomTimestamp = rand($startTimestamp, $endTimestamp);

        return date("Y-m-d", $randomTimestamp);
    }

}

?>