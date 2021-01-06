<?php

namespace EmangGek\CovidData;

use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class CovidData extends PluginBase
{
  public function onCommand(CommandSender $sender, Command $cmd, $label, array $args): bool
  {
    if ($cmd->getName() === 'coviddata') {
      if ($sender instanceof Player) {
        $this->coviddata($sender);
        return true;
      }
      $sender->sendMessage('Only player can execute this command');
      return false;
    }
  }

  public function coviddata($player)
  {
    $coviddata = json_decode(file_get_contents('http://api.covid19api.com/summary'), true);
    // covid data API
    $form = $this->getServer()->getPluginManager()->getPlugin("FormAPI")->createSimpleForm(function (Player $player, int $data = null) {
        // create a form
        $coviddata = json_decode(file_get_contents('http://api.covid19api.com/summary'), true);
        if ($data === null) {
          return true;
        }
        $this->showdata($player, $data);
        // execute function to show the data
    });
    $form->setTitle('CovidData');
    $form->setContent('Select country you want to see');
    foreach ($coviddata["Countries"] as $data) {
      // loop
      $form->addButton($data['Country']);
      // add button with country name
    }
    $form->sendToPlayer($player);
    // send the form to player
  }
  
  public function showdata($player, $index){
    // function to show the data
    $coviddata = json_decode(file_get_contents('http://api.covid19api.com/summary'), true)['Countries'][$index];
    $form = $this->getServer()->getPluginManager()->getPlugin("FormAPI")->createSimpleForm(function (Player $player, int $data = null) {
        return true;
      });
      $form->setTitle('CovidData - ' . $coviddata['Country']);
      $form->setContent($coviddata['TotalConfirmed'] . " Positives\n" . $coviddata['TotalRecovered'] . "Recovered\n" . $coviddata['TotalDeaths'] . 'Deaths');
      $form->addButton('Close');
      // set the content to data
      $form->sendToPlayer($player);
  }
}
