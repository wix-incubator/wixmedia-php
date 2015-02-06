<?php 

class WixImage extends WixMedia {
	const VERSION    = 'v1';

	const TRANSFORM_OP_CANVAS    = 'canvas';
	const TRANSFORM_OP_FILL      = 'fill';
	const TRANSFORM_OP_FIT       = 'fit';
	const TRANSFORM_OP_CROP      = 'crop';

	private $ADJUST_OP = array(
		'brightness' 	=> 'br',
		'contrast' 		=> 'con',
		'saturation'	=> 'sat',
		'hue' 			=> 'hue',
	);

	private $ALIGN_OP = array(
	    'center'		=> 'c',
	    'top'			=> 't',
	    'top-left'		=> 'tl',
	    'top-right'		=> 'tr',
	    'bottom'		=> 'b',
	    'bottom-left'	=> 'bl',
	    'bottom-right'	=> 'br',
	    'left'			=> 'l',
	    'right'			=> 'r',
	    'face'			=> 'f',
	    'faces'			=> 'fs'
	);

	private $RESIZE_FILTER = array(
		'PointFilter'  			=> 1,
		'BoxFilter'  			=> 2,
		'TriangleFilter'  		=> 3,
		'HermiteFilte'  		=> 4,
		'HanningFilter'  		=> 5,
		'HammingFilter'  		=> 6,
		'BlackmanFilter'  		=> 7,
		'GaussianFilter'  		=> 8,
		'QuadraticFilter'  		=> 9,
		'CubicFilter'  			=> 10,
		'CatromFilter'  		=> 11,
		'MitchellFilter'  		=> 12,
		'JincFilter'  			=> 13,
		'SincFilter' 			=> 14,
		'SincFastFilter'  		=> 15,
		'KaiserFilter'  		=> 16,
		'WelchFilter'  			=> 17,
		'ParzenFilter'  		=> 18,
		'BohmanFilter'  		=> 19,
		'BartlettFilter'  		=> 20,
		'LagrangeFilter'  		=> 21,
		'LanczosFilter'  		=> 22,
		'LanczosSharpFilter'	=> 23,
		'Lanczos2Filter'  		=> 24,
		'Lanczos2SharpFilter'  	=> 25,
		'RobidouxFilter'  		=> 26,
		'RobidouxSharpFilter'  	=> 27,
		'CosineFilter'  		=> 28,
	);

	private $service_host;
	private $trasform   = array();
	private $params		= array();

	public function __construct($image_id = '', $service_host, $client) {
		$this->service_host = $service_host;

		parent::__construct($image_id, $client);
	}

	public function getUrl() {
		if ($this->trasform) {
			$url = sprintf("http://%s/%s/%s/%s/%s", 
				$this->service_host, 
				pathinfo($this->id, PATHINFO_DIRNAME),
				self::VERSION,
				$command = $this->buildCommand(),
				pathinfo($this->id, PATHINFO_BASENAME)
			);
		} else {
			$url = sprintf("http://%s/%s", $this->service_host, $this->id);
		}
		return $url;
	}

	public function reset() {
		$this->trasform = array();
		$this->params = array();
	}

	public function canvas($width, $height, $alignment = 'center', $color = '000000') {
		$this->trasform['canvas'] = array(
			'w'		=> (int)$width,
			'h'		=> (int)$height,
			'al'	=> isset($this->ALIGN_OP[$alignment]) ? $this->ALIGN_OP[$alignment] : 'center',
			'c'		=> $color,
		);
		return $this;
	}

	public function fill($width, $height, $resize_filter = 'LanczosFilter', $alignment = 'center') {
		$this->trasform['fill'] = array(
			'w'		=> (int)$width,
			'h'		=> (int)$height,
			'rf'	=> isset($this->RESIZE_FILTER[$resize_filter]) ? $this->RESIZE_FILTER[$resize_filter] : 'LanczosFilter',
			'al'	=> isset($this->ALIGN_OP[$alignment]) ? $this->ALIGN_OP[$alignment] : 'center',
		);
		return $this;
	}

	public function fit($width, $height, $resize_filter = 'LanczosFilter') {
		$this->trasform['fit'] = array(
			'w'		=> (int)$width,
			'h'		=> (int)$height,
			'rf'	=> isset($this->RESIZE_FILTER[$resize_filter]) ? $this->RESIZE_FILTER[$resize_filter] : 'LanczosFilter',
		);
		return $this;
	}

	public function crop($x, $y, $width, $height) {
		$this->trasform['crop'] = array(
			'x'		=> (int)$x,
			'y'		=> (int)$y,
			'w'		=> (int)$width,
			'h'		=> (int)$height
		);
		return $this;
	}

	public function adjust($brightness = 0, $contrast = 0, $saturation = 0, $hue = 0) {
		if ($brightness) $this->params['br'] = $brightness;
		if ($contrast) $this->params['con'] = $contrast;
		if ($saturation) $this->params['sat'] = $saturation;
		if ($hue) $this->params['hue'] = $hue;
		return $this;
	}

	public function filter($oil = 0, $negate = 0, $pixelate = 0, $sharpen = 0, $unsharp_mask = 0) {
		if ($oil) $this->params['oil'] = $oil;
		if ($negate) $this->params['neg'] = $negate;
		if ($pixelate) $this->params['pix'] = $pixelate;
		if ($sharpen) $this->params['shrp'] = $sharpen;
		if ($unsharp_mask) $this->params['usm'] = $unsharp_mask;
		return $this;
	}

	public function oil() {
		$this->params['oil'] = 'oil';
		return $this;
	}
	
	public function negate() {
		$this->params['neg'] = 'neg';
		return $this;
	}
	
	public function pixelate($value) {
		$this->params['pix'] = $value;
		return $this;
	}
	
	public function blur($value) {
		$this->params['blur'] = $value;
		return $this;
	}
	
	public function sharp($value) {
		$this->params['shrp'] = $value;
		return $this;
	}

	public function baseline() {
		$this->params['bl'] = 'bl';
		return $this;
	}
	
	public function quality($value = 70) {
		$this->params['q'] = $value;
		return $this;
	}

	public function unsharp($radius = 0.5, $amount = 0.2, $threshold = 0.0) {
		$radius = number_format ($radius, 2);
		$amount = number_format ($amount, 2);
		$threshold = number_format ($threshold, 2);
		$this->params['usm'] = array($radius, $amount, $threshold);
		return $this;
	}

	private function buildCommand() {
		$command = '';
		$param_glue = ',';
		$transform_glue = '/';
		foreach ($this->trasform as $transformation => $trans_values) {
			$command .= $transformation . $transform_glue . $this->glueCommandParams($trans_values, $param_glue) . $transform_glue;
		}
		$command = substr($command, 0, -1);
		if ($this->params) $command .= $param_glue . $this->glueCommandParams($this->params, $param_glue); 
		return $command;
	}

	private function glueCommandParams($params, $glue = ',') {
		$params_str = '';
		foreach ($params as $pname => $pvalue) {
			if (is_array($pvalue)) {
				$params_str .= $pname .'_'. implode('_', $pvalue);
			} else {
				if ($pvalue === $pname) {
					$params_str .= $pname;
				} else {
					$params_str .= $pname .'_'. $pvalue;
				}
			}
			$params_str .= $glue;
		}
		return substr($params_str, 0, -1);
	}
}
