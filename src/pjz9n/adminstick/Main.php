<?php

/*
 * Copyright (c) 2022 PJZ9n.
 *
 * This file is part of AdminStick.
 *
 * AdminStick is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * AdminStick is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with AdminStick. If not, see <http://www.gnu.org/licenses/>.
 */

declare(strict_types=1);

namespace pjz9n\adminstick;

use pjz9n\adminstick\form\AdminStickMenu;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

class Main extends PluginBase implements Listener
{
    public const ADMIN_STICK_NAME = TextFormat::BOLD . TextFormat::YELLOW . "ADMIN STICK";

    public static function createAdminStick(): Item
    {
        return VanillaItems::STICK()->setCustomName(self::ADMIN_STICK_NAME);
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        if (!($sender instanceof Player)) {
            $sender->sendMessage(TextFormat::RED . "Run this command from within the game");
            return true;
        }
        $sender->getInventory()->addItem(self::createAdminStick());
        $sender->sendMessage(TextFormat::AQUA . "You got the AdminStick!");
        return true;
    }

    public function onUseAdminStick(PlayerItemUseEvent $event): void
    {
        $player = $event->getPlayer();
        $inventory = $player->getInventory();
        if ($inventory->getItemInHand()->getCustomName() === TextFormat::BOLD . TextFormat::YELLOW . "ADMIN STICK") {
            if ($player->hasPermission("pjz9n.adminstick.stick.use")) {
                $player->sendForm(new AdminStickMenu());
            }
        }
    }

    protected function onEnable(): void
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }
}
