<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPInterface.php to edit this template
 */
namespace FriendsOfRedaxo\MediapoolExif\Formatter\Interface;

/**
 *
 * @author akrys
 */
interface StandardFormatterInterface extends FormatterInterface
{

	/**
	 * Standard-Formatter
	 *
	 * @param array<string, mixed> $exifData
	 * @return string
	 */
	public function format(array $exifData): string;
}