<?php

enum CEFRLevel: string {
    case A1 = 'Beginner';
    case A2 = 'Elementary';
    case B1 = 'Intermediate';
    case B2 = 'Upper Intermediate';
    case C1 = 'Advanced';
    case C2 = 'Proficient';

    public static function fromInt(int $level): ?self {
        return match (true) {
            $level >= 0 && $level <= 2 => self::A1,
            $level >= 3 && $level <= 4 => self::A2,
            $level >= 5 && $level <= 6 => self::B1,
            $level >= 7 && $level <= 8 => self::B2,
            $level == 9 => self::C1,
            $level == 10 => self::C2,
            default => null,
        };
    }
}