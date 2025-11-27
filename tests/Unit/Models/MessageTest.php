<?php

use App\Models\Message;
use App\Models\User;
use App\Models\Item;

test('message can be created with valid data', function () {
    $sender = User::factory()->create();
    $receiver = User::factory()->create();
    $item = Item::factory()->create();

    $message = Message::factory()->create([
        'SenderID' => $sender->UserID,
        'ReceiverID' => $receiver->UserID,
        'ItemID' => $item->ItemID,
        'MessageContent' => 'Hello, is this item available?',
        'IsRead' => false,
    ]);

    expect($message)->toBeInstanceOf(Message::class)
        ->and($message->SenderID)->toBe($sender->UserID)
        ->and($message->ReceiverID)->toBe($receiver->UserID)
        ->and($message->ItemID)->toBe($item->ItemID)
        ->and($message->MessageContent)->toBe('Hello, is this item available?')
        ->and($message->IsRead)->toBeFalse();
});

test('message belongs to sender', function () {
    $sender = User::factory()->create();
    $message = Message::factory()->create(['SenderID' => $sender->UserID]);

    expect($message->sender)->toBeInstanceOf(User::class)
        ->and($message->sender->UserID)->toBe($sender->UserID);
});

test('message belongs to receiver', function () {
    $receiver = User::factory()->create();
    $message = Message::factory()->create(['ReceiverID' => $receiver->UserID]);

    expect($message->receiver)->toBeInstanceOf(User::class)
        ->and($message->receiver->UserID)->toBe($receiver->UserID);
});

test('message belongs to item', function () {
    $item = Item::factory()->create();
    $message = Message::factory()->create(['ItemID' => $item->ItemID]);

    expect($message->item)->toBeInstanceOf(Item::class)
        ->and($message->item->ItemID)->toBe($item->ItemID);
});

test('message can be marked as read', function () {
    $message = Message::factory()->read()->create();

    expect($message->IsRead)->toBeTrue();
});

test('message conversation scope retrieves messages between two users', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $user3 = User::factory()->create();

    // Messages between user1 and user2
    $message1 = Message::factory()->create([
        'SenderID' => $user1->UserID,
        'ReceiverID' => $user2->UserID,
        'SentAt' => now()->subHours(3),
    ]);

    $message2 = Message::factory()->create([
        'SenderID' => $user2->UserID,
        'ReceiverID' => $user1->UserID,
        'SentAt' => now()->subHours(2),
    ]);

    $message3 = Message::factory()->create([
        'SenderID' => $user1->UserID,
        'ReceiverID' => $user2->UserID,
        'SentAt' => now()->subHours(1),
    ]);

    // Message with different user (should not be included)
    Message::factory()->create([
        'SenderID' => $user1->UserID,
        'ReceiverID' => $user3->UserID,
    ]);

    $conversation = Message::conversation($user1->UserID, $user2->UserID)->get();

    expect($conversation)->toHaveCount(3)
        ->and($conversation->first()->MessageID)->toBe($message1->MessageID)
        ->and($conversation->last()->MessageID)->toBe($message3->MessageID);
});

test('message sent at is cast to datetime', function () {
    $message = Message::factory()->create();

    expect($message->SentAt)->toBeInstanceOf(\Carbon\Carbon::class);
});

test('message is read is cast to boolean', function () {
    $message = Message::factory()->create(['IsRead' => true]);

    expect($message->IsRead)->toBeBool();
});
