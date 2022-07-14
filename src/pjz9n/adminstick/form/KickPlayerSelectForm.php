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

namespace pjz9n\adminstick\form;

use pjz9n\adminstick\Main;
use pjz9n\advancedform\button\handler\builtin\BackHandler;
use pjz9n\advancedform\button\image\PathButtonImage;
use pjz9n\advancedform\button\ImageButton;
use pjz9n\advancedform\menu\MenuForm;
use pjz9n\advancedform\menu\response\MenuFormResponse;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class KickPlayerSelectForm extends MenuForm
{
    public function __construct()
    {
        parent::__construct(
            Main::ADMIN_STICK_NAME . TextFormat::AQUA . " - Kick",
            "Select the player you want to kick",
        );
        foreach (Server::getInstance()->getOnlinePlayers() as $onlinePlayer) {
            $this->appendButton(new ImageButton($onlinePlayer->getName(), new PathButtonImage("textures/ui/icon_steve"), value: $onlinePlayer));
        }
        $backButton = new ImageButton("Back", new PathButtonImage("textures/blocks/barrier"), new BackHandler([AdminStickMenu::class]));
        //Add buttons top and bottom
        $this->prependButton($backButton);
        $this->appendButton($backButton);
    }

    protected function handleSelect(Player $player, MenuFormResponse $response): void
    {
        /** @var Player $selectedPlayer */
        $selectedPlayer = $response->getSelectedButton()->getValue();
        $player->sendForm(new KickConfirmForm($selectedPlayer));
    }
}
