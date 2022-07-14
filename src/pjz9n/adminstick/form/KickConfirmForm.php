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
use pjz9n\advancedform\button\Button;
use pjz9n\advancedform\button\handler\builtin\BackHandler;
use pjz9n\advancedform\chain\PlayerFormChainMap;
use pjz9n\advancedform\modal\ModalForm;
use pjz9n\advancedform\modal\response\ModalFormResponse;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class KickConfirmForm extends ModalForm
{
    public function __construct(private Player $target)
    {
        parent::__construct(
            Main::ADMIN_STICK_NAME . TextFormat::AQUA . " - Kick/Confirm",
            "Are you sure you want to kick Player {$target->getName()}?",
            noButton: new Button("Back", new BackHandler([KickPlayerSelectForm::class]))
        );
    }

    protected function handleSelect(Player $player, ModalFormResponse $response): void
    {
        if ($response->isYesButton()) {
            $this->target->kick();
            $player->sendForm((new AdminStickMenu())->clean()->appendMessage(TextFormat::GREEN . "Kicked {$this->target->getName()}!"));
        } else {
            PlayerFormChainMap::get($player)->sendBack($player, [KickPlayerSelectForm::class]);
        }
    }
}
