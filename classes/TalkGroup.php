<?php
class TalkGroup extends BaseDBObject
{
	const DB_FIELDS = [
		'TGID',
		'alpha_tag',
		'mode',
		'description',
		'tag',
		'category',
	];
	const DB_KEY = 'TGID';

	//const DB_TABLE = '??';

	public static function createFromCSVLine($csv_line): TalkGroup
	{
		/*
			Decimal,Hex,Alpha Tag,Mode,Description,Tag,Category
			1037,40d,EC DOC T1,D,County DOC Transport 1,Corrections,Essex County
		*/
		// TODO: add support for trunk-recorder format
		$columns = explode(',', $csv_line);
		$params = [
			'TGID'         => $columns[0],
			// TGID_hex $csv_line[1] (unused)
			'alpha_tag'    => $columns[2],
			'mode'         => $columns[3],
			'descriptiopn' => $columns[4],
			'tag'          => $columns[5],
			'category'     => $columns[6],
		];

		return new TalkGroup(['record' => $params]);
	}

}
