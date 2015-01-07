<?php

namespace _64FF00\PurePerms\commands;

use _64FF00\PurePerms\PurePerms;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;

use pocketmine\OfflinePlayer;

use pocketmine\Player;

use pocketmine\utils\TextFormat;

class SetGroup extends Command implements PluginIdentifiableCommand
{
	public function __construct(PurePerms $plugin, $name, $description)
	{
		$this->plugin = $plugin;
		
		parent::__construct($name, $description);
		
		$this->setPermission("pperms.command.setgroup");
	}
	
	public function execute(CommandSender $sender, $label, array $args)
	{
		if(!$this->testPermission($sender))
		{
			return false;
		}
		
		if(count($args) < 2 || count($args) > 3)
		{
			$sender->sendMessage(TextFormat::BLUE . "[PurePerms] " . $this->plugin->getMessage("cmds.setgroup.usage"));
			
			return true;
		}
		
		$player = $this->plugin->getPlayer($args[0]);
		
		$group = $this->plugin->getGroup($args[1]);
		
		if($group == null) 
		{
			$sender->sendMessage(TextFormat::RED . "[PurePerms] " . $this->plugin->getMessage("cmds.setgroup.messages.group_not_exist", $args[1]));
			
			return true;
		}
		
		$levelName = (( isset($args[2]) && ($this->plugin->getServer()->getLevelByName($args[2]) != null) ) ?  $this->plugin->getServer()->getLevelByName($args[2])->getName() : null;
		
		$this->plugin->setGroup($player, $group, $levelName);
		
		$sender->sendMessage(TextFormat::BLUE . "[PurePerms] " . $this->plugin->getMessage("cmds.setgroup.messages.setgroup_successfully", $player->getName()));
		
		if($player instanceof Player)
		{
			$message = $this->plugin->getPPConfig()->getValue("msg-on-group-change");
			
			$message = str_replace("%group%", strtolower($group->getName()), $message);
			
			$player->sendMessage(TextFormat::BLUE . "[PurePerms] " . $message);
		}
		
		return true;
	}
	
	public function getPlugin()
	{
		return $this->plugin;
	}
}
