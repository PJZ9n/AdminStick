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
use pjz9n\advancedform\button\handler\builtin\IgnoreHandler;
use pjz9n\advancedform\button\image\PathButtonImage;
use pjz9n\advancedform\button\ImageButton;
use pjz9n\advancedform\menu\MenuForm;
use pjz9n\advancedform\menu\response\MenuFormResponse;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class AdminStickMenu extends MenuForm
{
    public function __construct()
    {
        parent::__construct(
            Main::ADMIN_STICK_NAME . TextFormat::AQUA . " MENU",
            "What do you want to do?",
        );
        $this
            ->appendButton(new ImageButton("Kick the player", new PathButtonImage("textures/ui/anvil_icon"), name: "kick"))
            ->appendButton(new ImageButton("Give the admin stick", new PathButtonImage("textures/items/stick"), name: "give_stick"))
            // Using ignoreHandler, handleSelect is not called
            ->appendButton(new ImageButton("Close", new PathButtonImage("textures/blocks/barrier"), new IgnoreHandler()));
    }

    protected function handleSelect(Player $player, MenuFormResponse $response): void
    {
        switch ($response->getSelectedButton()->getName()) {
            case "kick":
                $player->sendForm(new KickPlayerSelectForm());
                break;
            case "give_stick":
                $player->sendForm(new GiveAdminStick());
                break;
        }
    }
}
