<?php

/**
 * Datei für ...
 *
 * @version       1.0 / 2020-06-08
 * @author        akrys
 */
namespace FriendsOfRedaxo\addon\MediapoolExif;

use FriendsOfRedaxo\addon\MediapoolExif\Exception\NotFoundException;
use FriendsOfRedaxo\addon\MediapoolExif\Exif;
use FriendsOfRedaxo\addon\MediapoolExif\Format\FormatInterface;

/**
 * Description of ExifData
 *
 * @author akrys
 */
class ExifData
{
	/**
	 * Media-Objekt
	 *
	 * @todo activate type hint if min PHP-Version > 7.4
	 * @var rex_media
	 */
	private /* rex_media */ $media;

	/**
	 * Exif-Daten-Array
	 *
	 * @todo activate type hint if min PHP-Version > 7.4
	 * @var rex_media
	 */
	private /* array */ $exif;

	/**
	 * Modus
	 * @var int
	 */
	private /* int */ $mode;

	/**
	 * Konstruktor
	 *
	 * Modus für die Fehlerbehandlung
	 * Standard: MODE_THROW_EXCEPTION
	 *
	 * Wer keine try/catch-Blocke mag, kann sich in dem Fall dann andere false-Werte liefern lassen.
	 *
	 * Die Gefahr, dass Code-Technisch nicht mehr erkannt werden kann, ob es ein Fehler gab oder ob der Wert
	 * tatsächlich 0 oder false oder was auch immer ist, liegt dann natürlich beim jeweiligen Entwickler.
	 * Garantierte Eindeutigkeit gibt es nur im Modus MODE_THROW_EXCEPTION. (Darum auch der Standard)
	 *
	 * <ul>
	 * <li>false (MODE_RETURN_FALSE)</li>
	 * <li>null (MODE_RETURN_NULL)</li>
	 * <li>0 (MODE_RETURN_ZERO)</li>
	 * <li>-1 (MODE_RETURN_MINUS)</li>
	 * <li>'' (MODE_RETURN_EMPTY_STRING)</li>
	 * <li>[] (MODE_RETURN_EMPTY_ARRAY)</li>
	 * </ol>
	 *
	 * @param \FriendsOfRedaxo\addon\MediapoolExif\rex_media $media
	 * @param int $mode
	 */
	public function __construct(rex_media $media, int $mode = null)
	{
		$this->media = $media;
		$this->exif = json_decode($this->media->getValue('exif'), true);
		if ($mode === null) {
			$mode = Exif::MODE_THROW_EXCEPTION;
		}
		$this->mode = $mode;
	}

	/**
	 * Daten holen
	 *
	 * Ist der Index nicht gesetzt, kommt alles in Form eines Arrays zurück.
	 *
	 * @param string $index
	 * @return mixed
	 * @throws NotFoundException
	 */
	public function get(string $index = null)
	{
		if ($index !== null) {
			if (!array_key_exists($index, $this->exif)) {
				return $this->handleExcption(new NotFoundException($index, 'Index not found: '.$index));
			}
			return $this->exif[$index];
		}

		return $data;
	}

	/**
	 * Formatierungsalgorithmus anstoßen
	 * @param string $type
	 * @param string $format
	 * @return mixed
	 */
	public function format(string $type, string $format = null)
	{
		try {
			return FormatInterface::get($this->exif, $type, $format)->format();
		} catch (Exception $e) {
			return $this->handleExcption($e);
		}
	}

	/**
	 * Fehler-Behandlung
	 *
	 * Welche Rückgabe hätten's gern?
	 *
	 * @param string $exception
	 * @return mixed
	 * @throws NotFoundException
	 */
	private function handleExcption(Exception $exception)
	{
		$return = '';

		switch ($this->mode) {
			case Exif::MODE_RETURN_NULL:
				$return = null;
				break;
			case Exif::MODE_RETURN_FALSE:
				$return = false;
				break;
			case Exif::MODE_RETURN_ZERO:
				$return = 0;
				break;
			case Exif::MODE_RETURN_MINUS:
				$return = -1;
				break;
			case Exif::MODE_RETURN_EMPTY_STRING:
				$return = '';
				break;
			case Exif::MODE_RETURN_EMPTY_ARRAY:
				$return = [];
				break;
			case Exif::MODE_THROW_EXCEPTION:
			default:
				throw $exception;
		}
		return $return;
	}
}
