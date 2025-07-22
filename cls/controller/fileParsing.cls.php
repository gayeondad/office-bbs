<?php
/**
 * fileParsing : text file parsing
 */
namespace cls\controller;

class fileParsing {
	private $files = array();
	private $parsedTxt = array();

	private $regex = array(
		'qualification' => array('start' => '(\d{1,})(\.|\n)\s*(입찰|견적\s*제출|)\s*참가\s*자격.*', 'end' => 'DIGIT(\.|\n)\s*.+'),
		'' => '',
	);
	
	public function __construct()
	{
		
	}

	function __destruct()
	{

	}

	public main()
	{
		$finedItem = array();
		foreach ($this->files as $file) {
			$extrctLines = $this->extrctQualification($file);
			if (!empty($extrctLines)) {
				foreach ($extrctLines as $line) {
					if (preg_match('/(\d{10})/', $line, $matches)) {
						if (strpos($line, "공급") !== flase) {
							$type = "공급";
						}
						elseif (strpos($line, "제조") !== flase) {
							$type = "제조";
						}
						else {
							$type = "미확인";
						}
						if (array_key_exists($matches[1], $finedItem)) {
							if ($finedItem[$matches[1]] == "미확인") {
								$finedItem[$matches[1]] = $type;
							}
						}
						else {
							$finedItem[$matches[1]] = $type;
						}
						// $finedItem[] = array('code' => $matches[1], 'type' => $type);
					}
				}
			}
		}

		return $finedItem;
	}

	public function extrctQualification($str=array())
	{
		$copid = false;		// 라인 당 수집 여부
		$endNum = 0;		// 참가자격 다음 항목 번호
		// $delimiter = '';	// 항목 구분자('3. 참가자격...' => '.')
		$lines = array();	// 수집 라인

		foreach ($str as $key => $value) {
			$trimedLine = trim($value);
			if (empty($trimedLine)) continue;

			if ($endNum > 0) {
				if (preg_match('/' . str_replace("DIGIT", $endNum, $this->regex['qualification']['end']) . '/', $trimedLine)) {
					$copid = false;
				}
			}

			if (!empty($copid)) {
				$lines[] = $trimedLine;
			} else {
				if (preg_match('/' . $this->regex['qualification']['start'] . '/', $trimedLine, $matches)) {
					$copid = true;
					$endNum = intval($matches[1]) + 1;
					// $delimiter = $matches[2];
				}
			}
		}

		return $lines;
	}



}
