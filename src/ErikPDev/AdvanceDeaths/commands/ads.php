<?php

namespace ErikPDev\AdvanceDeaths\commands;

use ErikPDev\AdvanceDeaths\ADMain;
use ErikPDev\AdvanceDeaths\utils\database\databaseProvider;
use ErikPDev\AdvanceDeaths\utils\translationContainer;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;

class ads extends Command implements PluginOwned {

	public function __construct() {

		parent::__construct("ads", "Find the stats of a player!", translationContainer::translate("usage", false, []), ["advancedeaths"]);
		$this->setPermission("advancedeaths.use");

	}

	public function execute(CommandSender $sender, string $commandLabel, array $args): bool {

		if (count($args) == 0) {
			$sender->sendMessage("§l§5» §r§cUsage: /ads (PlayerName)");
			return false;
		}

		$responsePromise = databaseProvider::getAll(str_replace("%", "", $args[0]) . "%");
		$responsePromise->onCompletion(
			function (array $data) use ($sender) {
				$params = [];
				foreach ($data as $key => $value) {
					$params[$key."%"] = $value;
				}
				$sender->sendMessage(translationContainer::translate("adsCommandSuccess",true, $params));
			},
			function () use ($sender) {
				$sender->sendMessage(translationContainer::translate("adsCommandError", true, []));
			}
		);

		return true;
	}

	public function getOwningPlugin(): Plugin {

		return ADMain::getInstance();

	}

}
