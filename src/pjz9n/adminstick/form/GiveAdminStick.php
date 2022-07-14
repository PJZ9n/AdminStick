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
use pjz9n\advancedform\custom\CustomForm;
use pjz9n\advancedform\custom\element\Dropdown;
use pjz9n\advancedform\custom\element\handler\builtin\BackToggle;
use pjz9n\advancedform\custom\element\Input;
use pjz9n\advancedform\custom\element\SelectorOption;
use pjz9n\advancedform\custom\response\CustomFormResponse;
use pjz9n\advancedform\custom\result\exception\InvalidResponseException;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use function array_map;

class GiveAdminStick extends CustomForm
{
    public function __construct()
    {
        parent::__construct(Main::ADMIN_STICK_NAME . TextFormat::AQUA . " - Give");
        $this
            ->appendElement((new Dropdown("Give to", name: "give_to"))->appendOptions(array_map(function (Player $player): SelectorOption {
                return new SelectorOption($player->getName(), $player);
            }, Server::getInstance()->getOnlinePlayers())))
            ->appendElement(new Input("Amount", default: "1", name: "amount"))
            ->appendElement(new BackToggle("Back", [AdminStickMenu::class]));
    }

    protected function handleSubmit(Player $player, CustomFormResponse $response): void
    {
        /** @var Player $giveTo */
        $giveTo = $response->getSelectorResult("give_to")->getOptionValue();
        try {
            $amount = $response->getInputResult("amount")->getInt();
        } catch (InvalidResponseException) {
            $player->sendForm($this->clean()->setDefaults($response)->addErrorByName("amount", "amount must be integer"));
            return;
        }

        $giveTo->getInventory()->addItem(Main::createAdminStick());
        $player->sendForm((new AdminStickMenu())->clean()->appendMessage(TextFormat::GREEN . "Admin stick(s) given!"));
    }
}
