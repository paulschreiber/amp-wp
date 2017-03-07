<?php

/**
 * Class ExifParser
 *
 * @package FasterImage
 */
class Faster_Image_B52f1a8_Exif_Parser {

	/**
	 * @var int
	 */
	protected $width;
	/**
	 * @var  int
	 */
	protected $height;

	/**
	 * @var
	 */
	protected $short;

	/**
	 * @var
	 */
	protected $long;

	/**
	 * @var  StreamableInterface
	 */
	protected $stream;

	/**
	 * @var int
	 */
	protected $orientation;

	/**
	 * ExifParser constructor.
	 *
	 * @param StreamableInterface $stream
	 */
	public function __construct( Stream_17b32f3_Streamable_Interface $stream ) {
		$this->stream = $stream;
		$this->parse_exif_ifd();
	}

	/**
	 * @return int
	 */
	public function get_height() {
		return $this->height;
	}

	/**
	 * @return int
	 */
	public function get_width() {
		return $this->width;
	}

	/**
	 * @return bool
	 */
	public function is_rotated() {
		return ( ! empty( $this->orientation ) && $this->orientation >= 5);
	}

	/**
	 * @return bool
	 * @throws \FasterImage\Exception\InvalidImageException
	 */
	protected function parse_exif_ifd() {
		$byte_order = $this->stream->read( 2 );

		switch ( $byte_order ) {
			case 'II':
				$this->short = 'v';
				$this->long  = 'V';
				break;
			case 'MM':
				$this->short = 'n';
				$this->long  = 'N';
				break;
			default:
				throw new Faster_Image_B52f1a8_Invalid_Image_Exception;
				break;
		}

		$this->stream->read( 2 );

		$offset = current( unpack( $this->long, $this->stream->read( 4 ) ) );

		$this->stream->read( $offset - 8 );

		$tag_count = current( unpack( $this->short, $this->stream->read( 2 ) ) );

		for ( $i = $tag_count; $i > 0; $i-- ) {

			$type = current( unpack( $this->short, $this->stream->read( 2 ) ) );
			$this->stream->read( 6 );
			$data = current( unpack( $this->short, $this->stream->read( 2 ) ) );

			switch ( $type ) {
				case 0x0100:
					$this->width = $data;
					break;
				case 0x0101:
					$this->height = $data;
					break;
				case 0x0112:
					$this->orientation = $data;
					break;
			}

			if ( isset( $this->width ) && isset( $this->height ) && isset( $this->orientation ) ) {
				return true;
			}

			$this->stream->read( 2 );
		}

		return false;
	}
}