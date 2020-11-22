<?php

/*
 * This file is apart of the DiscordPHP project.
 *
 * Copyright (c) 2016-2020 David Cole <david.cole1340@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the LICENSE.md file.
 */

namespace Discord\Parts\Channel;

use Discord\Parts\Guild\Emoji;
use Discord\Parts\Part;

/**
 * Represents a reaction to a message by members(s).
 *
 * @property string $id The identifier of the reaction.
 * @property int $count Number of reactions.
 * @property bool $me Whether the current bot has reacted.
 * @property Emoji $emoji The emoji that was reacted with.
 * @property string $message_id The message ID the reaction is for.
 * @property Message|null $message The message the reaction is for.
 * @property string $channel_id The channel ID that the message belongs in.
 * @property Channel $channel The channel that the message belongs tol
 */
class Reaction extends Part
{
    /**
     * {@inheritdoc}
     */
    protected $fillable = ['count', 'me', 'emoji', 'message_id', 'channel_id'];

    /**
     * Gets the emoji identifier, either the `id` or `name`.
     *
     * @return string
     */
    protected function getIdAttribute(): string
    {
        if ($emoji = $this->emoji) {
            return ":{$emoji->name}:{$emoji->id}";
        }

        return '';
    }

    /**
     * Gets the partial emoji attribute.
     *
     * @return Emoji
     * @throws \Exception
     */
    protected function getEmojiAttribute(): ?Part
    {
        if (isset($this->attributes['emoji'])) {
            return $this->factory->create(Emoji::class, $this->attributes['emoji'], true);
        }

        return null;
    }

    /**
     * Gets the message attribute.
     *
     * @return Message|null
     */
    protected function getMessageAttribute(): ?Message
    {
        if ($channel = $this->channel) {
            return $channel->messages->offsetGet($this->message_id);
        }

        return null;
    }

    /**
     * Gets the channel attribute.
     *
     * @return Channel
     */
    protected function getChannelAttribute(): Channel
    {
        return $this->discord->getChannel($this->channel_id);
    }
}
