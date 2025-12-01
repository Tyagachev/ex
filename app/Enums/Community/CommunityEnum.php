<?php

namespace App\Enums\Community;

enum CommunityEnum: string
{
    case PUBLIC = 'public';
    case LIMIT = 'limit';
    case PRIVATE = 'private';

    public function access(): string
    {
        return match ($this) {
            self::PUBLIC => 'Публичное',
            self::LIMIT => 'С ограниченным доступом',
            self::PRIVATE => 'Закрытое',
        };
    }

    public function accessDiscription(): string
    {
        return match ($this) {
            self::PUBLIC => 'Все могут просматривать и добавлять посты и комментарии в этом сообществе.',
            self::LIMIT => 'Все могут просматривать посты и комментарии, но их добавление доступно только подтвержденным пользователям.',
            self::PRIVATE => 'Только подтвержденные пользователи могут просматривать и добавлять посты или комментарии.',
        };
    }
}
