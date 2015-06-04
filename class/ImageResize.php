<?php

/**
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL.
 *
 * @package     Ace
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @since       1.2.1
 * @author      David Zeller <zellerda01@gmail.com>
 */
class ImageResize {

	/**
     * Working image
     * @var ressource
     */
	protected $working_image;

	/**
     * Image infos
     * @var array
     */
	protected $info;

	/**
     * Original infos
     * @var array
     */
	protected $original_info;

	/**
     * Class constructor
     *
     * @param string $base_image The absolute path of the image
     */
	public function __construct($image_file) {
		if (extension_loaded ( 'gd' )) {
			if (file_exists ( $image_file )) {
				$info = getimagesize ( $image_file );
				
				// Create the image ressource depending of the mime type
				switch ($info ['mime']) {
					case 'image/png' :
						$base_image = imagecreatefrompng ( $image_file );
						break;
					case 'image/jpeg' :
						$base_image = imagecreatefromjpeg ( $image_file );
						break;
					case 'image/gif' :
						$old_id = imagecreatefromgif ( $image_file );
						$base_image = imagecreatetruecolor ( $info [0], $info [1] );
						imagecopy ( $base_image, $old_id, 0, 0, 0, 0, $info [0], $info [1] );
						break;
					default :
						break;
				}
				
				$this->info ['width'] = $info [0];
				$this->info ['height'] = $info [1];
				$this->info ['channels'] = $info ['channels'];
				$this->info ['bits'] = $info ['bits'];
				$this->info ['mime'] = $info ['mime'];
				
				$this->original_info = $this->info;
				$this->working_image = $base_image;
			} else {
				throw new RuntimeException ( 'Base file not found.' );
			}
		} else {
			throw new RuntimeException ( 'The "gd" extension in not loader in your php configuration.' );
		}
	}

	/**
     * Save the image
     *
     * @param integer $quality
     * @param string $filename The absolute path of the new image
     */
	public function save($filename, $quality = 9) {
		switch ($this->info ['mime']) {
			case 'image/png' :
				imagepng ( $this->working_image, $filename, 9 ) or die('image png failed.');
				break;
			case 'image/jpeg' :
				imagejpeg ( $this->working_image, $filename, 99 );
				break;
			case 'image/gif' :
				imagegif ( $this->working_image, $filename );
				break;
			default :
				break;
		}
		
		$this->clean ();
		return $this;
	}

	/**
     * Output the image
     *
     * @param integer $quality
     * @return Ace_Media_Image
     */
	public function output($quality = 100) {
		switch ($this->info ['mime']) {
			case 'image/png' :
				imagepng ( $this->working_image, null, $quality );
				break;
			case 'image/jpeg' :
				imagejpeg ( $this->working_image, null, $quality );
				break;
			case 'image/gif' :
				imagegif ( $this->working_image );
				break;
			default :
				break;
		}
		
		$this->clean ();
		return $this;
	}

	/**
     * Resize an image
     *
     * @param integer $dest_w Destination width
     * @param integer $dest_h Destination height
     * @param mixed $ratio Define the ratio mode (false = no ratio, W = ratio width, H = ratio height, B = both)
     * @return Ace_Media_Image
     */
	public function resize($dest_w, $dest_h, $ratio = false, $fill = false) {
		$leftFill = $rightFill = $topFill = $bottomFill = 0;
		if (strtoupper ( $ratio ) == 'W') {
			$ratio_w = $dest_w / $this->info ['width'];
			$dest_h = $this->info ['height'] * $ratio_w;
		} else if (strtoupper ( $ratio ) == 'H') {
			if ($fill === true) {
				$ratio_h = $dest_h / $this->info ['height'];
				echo $leftFill = $rightFill = ($dest_w - $this->info ['width'] * $ratio_h) * 0.5;
			} else {
				$ratio_h = $dest_h / $this->info ['height'];
				$old_dest_w = $dest_w;
				$dest_w = $this->info ['width'] * $ratio_h;
			}
			
		} else if (strtoupper ( $ratio ) == 'B') {
			if ($this->info ['height'] < $this->info ['width']) {
				$this->resize ( $dest_w, $dest_h, 'W', $fill );
			} else {
				if ($dest_w < $dest_h) {
					$this->resize ( $dest_w, $dest_h, 'W' , $fill);
				} else {
					$this->resize ( $dest_w, $dest_h, 'H', $fill );
				}
			}
			
			return $this;
		}
		
		$new_image = imagecreatetruecolor ( $dest_w, $dest_h ) or die('create true color failed.');
		
		imagealphablending ( $new_image, false ) or die('alphablending failed.');
		imagesavealpha($new_image, true);
		imagecopyresampled ( $new_image, $this->working_image, $leftFill, 0, 0, 0, $dest_w, $dest_h, $this->info ['width'], $this->info ['height'] ) or die('copy resampled failed.');
		
		$this->working_image = $new_image;
		
		$this->info ['width'] = imagesx ( $new_image );
		$this->info ['height'] = imagesy ( $new_image );
		
		return $this;
	}

	/**
     * Rotate the image
     *
     * @param integer $angle
     * @return Ace_Media_Image
     */
	public function rotate($angle) {
		$this->working_image = imagerotate ( $this->working_image, $angle, 0 );
		return $this;
	}

	/**
     * Flip the image
     *
     * @param string $direction Direction (H = horizontal, V = vertical, B = both)
     * @return Ace_Media_Image
     */
	public function flip($direction = 'H') {
		$new_image = imagecreatetruecolor ( $this->info ['width'], $this->info ['height'] );
		
		if (strtoupper ( $direction ) == 'H') {
			for($x = 0; $x < $this->info ['width']; $x ++) {
				imagecopy ( $new_image, $this->working_image, $this->info ['width'] - $x - 1, 0, $x, 0, 1, $this->info ['height'] );
			}
		} else if (strtoupper ( $direction ) == 'V') {
			for($y = 0; $y < $this->info ['height']; $y ++) {
				imagecopy ( $new_image, $this->working_image, 0, $this->info ['height'] - $y - 1, 0, $y, $this->info ['width'], 1 );
			}
		} else {
			$this->flip ( 'H' )->flip ( 'V' );
			return $this;
		}
		
		$this->working_image = $new_image;
		return $this;
	}

	/**
     * Crop the image
     *
     * @param integer $base_width This is the width of the reference (sample: The width of the crop container)
     * @param integer $dest_w Destination width
     * @param integer $dest_h Destination height
     * @param integer $top Top position (x)
     * @param integer $left Left position (y)
     * @param integer $w Crop width
     * @param integer $h Crop height
     * @return Ace_Media_Image
     */
	public function crop($base_width, $dest_w, $dest_h, $x = 0, $y = 0, $w = 0, $h = 0) {
		$w_ratio = $this->info ['width'] / $base_width;
		$h_ratio = $this->info ['height'] / ($this->info ['height'] / $w_ratio);
		
		if ($w == 0) {
			$w = $dest_w * $w_ratio;
		} else {
			$w = $w * $w_ratio;
		}
		
		if ($h == 0) {
			$h = $dest_h * $h_ratio;
		} else {
			$h = $h * $h_ratio;
		}
		
		$y = $y * $w_ratio;
		$x = $x * $h_ratio;
		
		$new_image = imagecreatetruecolor ( $dest_w, $dest_h );
		imagecopyresampled ( $new_image, $this->working_image, 0, 0, $x, $y, $dest_w, $dest_h, $w, $h );
		
		$this->working_image = $new_image;
		$this->info ['width'] = imagesx ( $new_image );
		$this->info ['height'] = imagesy ( $new_image );
		
		return $this;
	}

	/**
     * Cut the image
     *
     * @param integer $dest_w Destination image width
     * @param type $dest_h Destination image height
     * @param type $h_align Horizontal position (L = left, C = center, R = right)
     * @param type $v_align Vertical position (T = top, M = middle, B = bottom)
     * @return Ace_Media_Image
     */
	public function cut($dest_w, $dest_h, $h_align = 'C', $v_align = 'T') {
		$w_ratio = $dest_w / $this->info ['width'];
		$h_ratio = $dest_h / $this->info ['height'];
		$h_align = strtoupper ( $h_align );
		$v_align = strtoupper ( $v_align );
		
		if ($this->info ['width'] > $this->info ['height'] || ($dest_h / $w_ratio) > $this->info ['height']) {
			if ($dest_w > $dest_h && ($this->info ['height'] * $w_ratio) > $dest_h) {
				$this->crop ( ($this->info ['width'] * $w_ratio), $dest_w, $dest_h, $this->getX ( $h_align, $w_ratio, $dest_w ), $this->getY ( $v_align, $w_ratio, $dest_h ) );
			} else {
				$this->crop ( ($this->info ['width'] * $h_ratio), $dest_w, $dest_h, $this->getX ( $h_align, $h_ratio, $dest_w ), $this->getY ( $v_align, $h_ratio, $dest_h ) );
			}
		} else {
			$this->crop ( $this->info ['width'] * $w_ratio, $dest_w, $dest_h, 0, $this->getY ( $v_align, $w_ratio, $dest_w ) );
		}
		
		return $this;
	}

	/**
     * Get X position for crop
     *
     * @param string $align
     * @param float $ratio
     * @param integer $dest
     * @return float
     */
	protected function getX($align = 'C', $ratio, $dest) {
		if ($align == 'L') {
			return 0;
		} else if ($align == 'R') {
			return ($this->info ['width'] * $ratio) - $dest;
		} else {
			return (($this->info ['width'] * $ratio) / 2) - ($dest / 2);
		}
	}

	/**
     * Get Y position for crop
     *
     * @param string $align
     * @param float $ratio
     * @param integer $dest
     * @return float
     */
	protected function getY($align = 'T', $ratio, $dest) {
		if ($align == 'T') {
			return 0;
		} else if ($align == 'B') {
			return ($this->info ['height'] * $ratio) - $dest;
		} else {
			return (($this->info ['height'] * $ratio) / 2) - ($dest / 2);
		}
	}

	/**
     * Return the base image height
     *
     * @return integer
     */
	public function getImageHeight() {
		return $this->info ['height'];
	}

	/**
     * Return the base image width
     *
     * @return integer
     */
	public function getImageWidth() {
		return $this->info ['width'];
	}

	/**
     * Return the mime type of the image
     *
     * @return string
     */
	public function getMimeType() {
		return $this->info ['mime'];
	}

	/**
     * Clean memory
     */
	protected function clean() {
		imagedestroy ( $this->working_image );
	}

	/**
     * Create an intance of the image class from a base image
     *
     * @param string $base_image The absolute path of the image
     * @return Ace_Media_Image
     */
	public static function with($base_image) {
		return new self ( $base_image );
	}
}