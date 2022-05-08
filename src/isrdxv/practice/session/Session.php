<?php

namespace isrdxv\practice\session;

use pocketmine\utils\TextFormat;
use pocketmine\player\Player;

// Items
use pocketmine\item\{
  Item,
  ItemIds,
  ItemIdentifier
};

use isrdxv\practice\Loader;
use isrdxv\practice\provider\YAMLProvider;

use libs\scoreboard\type\LobbyScoreboard;
use libs\scoreboard\Scoreboard;

class Session
{
  
  private Player $player;
  
  /** @var Scoreboard|null **/
  private ?Scoreboard $scoreboard;
  
  private array $settings;
  
  public function __construct(Player $player) 
  {
    $this->player = $player;
    $this->scoreboard = new LobbyScoreboard($player);
    $this->settings = Loader::getInstance()->getProvider()->loadSettings($player->getName());
  }
  
  public function getPlayer(): Player
  {
    return $this->player;
  }
  
  public function getScoreboard(): Scoreboard
  {
    return $this->scoreboard;
  }
  
  public function isScoreboard(): bool
  {
    return isset($this->scoreboard);
  }
  
  public function setScoreboard(Scoreboard $scoreboard): void
  {
    if (!$this->getPlayer()->isOnline()) {
      return;
    }
    $this->scoreboard = $scoreboard;
    $scoreboard->show();
    $scoreboard->showLines();
  }
  
  public function changeScoreboard(): void
  {
    if ($this->getSetting("score") !== true) {
      $this->getScoreboard()->remove();
      return;
    }
    $this->setScoreboard($this->scoreboard);
  }
  
  public function getSettings(): array
  {
    return $this->settings;
  }
  
  public function getSetting(string $setting): bool
  {
    return $this->settings[$setting];
  }
  
  public function setSetting(string $setting, bool $value = false): void
  {
    $this->settings[$setting] = $value;
  }
  
  public function setSettings(array $data): void
  {
    $this->settings = $data;
  }
  
  public function giveLobbyItems(): void
  {
    $ranked = new Item(new ItemIdentifier(ItemIds::DIAMOND_SWORD, 0));
    $unranked = new Item(new ItemIdentifier(ItemIds::IRON_SWORD, 0));
    $ffa = new Item(new ItemIdentifier(ItemIds::STONE_AXE, 0));
    $party = new Item(new ItemIdentifier(ItemIds::SKULL, 3));
    $profile = new Item(new ItemIdentifier(ItemIds::BOOK, 0));
    $ranked->setCustomName(TextFormat::colorize("&l&fRanked &cQueue"));
    $unranked->setCustomName(TextFormat::colorize("&l&fUnRanked &cQueue"));
    $ffa->setCustomName(TextFormat::colorize("&l&fFFA &cQueue"));
    $party->setCustomName(TextFormat::colorize("&l&fParty"));
    $profile->setCustomName(TextFormat::colorize("&l&fSettings"));
    $this->getPlayer()->getInventory()->clearAll();
    $this->getPlayer()->getInventoryArmor()->clearAll();
    $this->getPlayer()->getInventory()->setItem(0, $ranked);
    $this->getPlayer()->getInventory()->setItem(1, $unranked);
    $this->getPlayer()->getInventory()->setItem(2, $ffa);
    $this->getPlayer()->getInventory()->setItem(4, $party);
    $this->getPlayer()->getInventory()->setItem(8, $profile);
  }
  
  public function giveQueueItems(): void
  {
    $leave = new Item(new ItemIdentifier(ItemIds::COMPASS, 0));
    $leave->setCustomName(TextFormat::colorize("&l&fLeave Queue"));
    $this->getPlayer()->getInventory()->clearAll();
    $this->getPlayer()->getInventory()->setItem(4, $leave);
  }
  
}