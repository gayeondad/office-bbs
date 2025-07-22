<?php
$mapping = array();
$mapping['ConsultingBidTable'] = array(
	'Site' => 'Site',
	'BidNo' => 'BidNo',
	'BidNoSeq' => 'BidNoSeq',
	'RegDate' => 'RegDate',
	'EDate' => 'AnalCompleteDate',
	'MemID' => 'MemID',
	'MasterID' => 'MasterID',
	'SelectMoney1' => 'SelectMoney1',
	'YegaPok1' => 'YegaLow',
	'YegaPok2' => 'YegaHigh',
	'Percent' => 'CutPercent',
	'BasicMoney' => 'BasicMoney',
	'Rank' => 'SelectRank',
	'SelectPercent' => 'SelectPercent',
	'ESelectMoney' => 'RealMoney',
	'ERank' => 'RealRank',
	'ESelectPercent' => 'RealPercent',
	'ESMoneyGap' => 'RealGap',
	'RComName' => 'WinCorp',
	'RCeoName' => 'WinCeo',
	'RBizNo' => 'WinBizNo',
	'RMoney' => 'WinMoney',
	'RMoneyGap' => 'SelectGap',
	'RPercent' => 'WinRate',
	'RMemo' => 'SelectMemo',
	'State' => 'State',
	'chkTuchal' => 'chkTuchal',
	'chkAuto' => 'chkAuto',
	'F_Result' => 'finalWin',
	'chkSignUp' => 'chkSignUp',
	'viewDate' => 'ViewDate',
	'chkJae' => 'RBidDate'
);

$query = "SELECT * FROM ConsultingBidTable WHERE 1=1 ORDER BY Code";

$rs = $db1->execute($query);
if ($rs->fields) {
	while ($data = $rs->fetchrow()) {
		$row = array();
		foreach ($mapping as $key => $value) {
			$cnm[] = $key;
			if (in_array($key, array('chkTuchal','chkSignUp',''))) {
				$val[] = ($value == '1') ? 'Y' : 'N';
			}
			elseif ($key == 'chkAuto') {
				$val[] = ($value == 'A') ? 'Y' : 'N';
			}
			elseif ($key == 'F_Result') {
				$val[] = ($value == 'T') ? 'Y' : 'N';
			}
			elseif (in_array($key, array('RegDate','EDate',''))) {
				$val[] = (empty($value)) ? '0000-00-00 00:00:00' : $value;
			}
			else {
				$val[] = $value;
			}
		}
	}
}



