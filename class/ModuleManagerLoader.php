<?php
	require_once 'ConfigManager.php';

	require_once 'Others/Std.php';
	require_once 'Others/Json.php';

	trait ModuleManagerLoader
	{
		// LoadModules() => return loaded modules
		public function LoadModules()
		{
			Std::Out();
			Std::Out('[INFO] [MODULES] Loading');

			$Modules = Config::Get('Modules');

			if(is_array($Modules))
			{ // Show number of loaded modules
				$Keys = array_keys($Modules);

				foreach($Keys as $Key)
					foreach($Modules[$Key] as $Module)
						$this->LoadModule($Key, $Module);

				Std::Out('[INFO] [MODULES] Ready!');

				return true;
			}

			Std::Out('[WARNING] [MODULES] Config file is not an array');

			return false;
		}

		private function LoadModule($Key, $Name)
		{
			if(is_array($Name) && !empty($Name[0]) && !empty($Name[1])) // If some is empty?
			{
				$Filename = $Name[1];
				$Name = $Name[0];
			}
			else
			{
				$Filename = $Name;
			}

			if($this->KeyExists($Key))
			{
				$Path = "class/Modules/{$Key}_{$Filename}";

				$JPath = "{$Path}.json";
				$PPath = "{$Path}.php";

				if(basename(dirname(realpath($JPath))) === 'Modules') // Use Path::
				{
					$Json = Json::Read($JPath); // Show errors

					if($Json !== false)
					{
						if(is_readable($PPath)) // Lint
						{
							$this->Modules[$Key][strtolower($Name)] = array
							(
								'Path' => $PPath,
								'File' => $Filename,
								'Data' => $Json
							);

							return true;
						}
						else
							Std::Out("[WARNING] [MODULES] Can't load {$Key}::{$Name}. PHP file doesn't exists");
					}
					else
						Std::Out("[WARNING] [MODULES] Can't load {$Key}::{$Name}. Json file is not readable");
				}
				else
					Std::Out("[WARNING] [MODULES] Can't load {$Key}::{$Name}. It is not in Modules folder");
			}

			return false;
		}

		public function LoadCommandModule($Name)
		{
			return $this->LoadModule('Command', $Name);
		}

		public function LoadDomainModule($Name)
		{
			return $this->LoadModule('Domain', $Name);
		}

		public function LoadExtensionModule($Name)
		{
			return $this->LoadModule('Extension', $Name);
		}

		public function LoadMediaModule($Name)
		{
			return $this->LoadModule('Media', $Name);
		}
	}