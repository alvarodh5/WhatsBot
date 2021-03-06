<?php
	require_once 'Std.php';

	class Json
	{ // Non static context => new Json($Filename) => ->Read() ->Write($Data)
		public static function Read($Filename)
		{
			if(is_readable($Filename))
			{
				$Data = file_get_contents($Filename);

				if($Data !== false)
				{
					$Data = json_decode($Data, true);

					if($Data !== null)
					{
						return $Data;
					}
					else
					{
						Std::Out("[WARNING] [JSON] Can't decode {$Filename}");
						return;
					}
				}
			}

			Std::Out("[WARNING] [JSON] No such file {$Filename}");

			return false;
		}

		public static function Write($Filename, $Data, $Options = JSON_PRETTY_PRINT)
		{
			$Data = json_encode($Data, $Options);

			if($Data !== false)
			{
				$ToWrite = strlen($Data);
				$Writed = file_put_contents($Filename, $Data);

				if($Writed === $ToWrite)
					return true;
				else
					Std::Out("[WARNING] [JSON] {$Filename} {$Writed} bytes writed of {$ToWrite}");
			}
			else
				Std::Out("[WARNING] [JSON] Can't encode {$Filename}");

			return false;
		}
	}