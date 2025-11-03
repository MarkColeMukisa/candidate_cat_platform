<?php

namespace App\Filament\Resources\Candidates\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CandidateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('phone')
                    ->tel(),

                // Assessment questionnaire (stored as JSON via page mutator)
                Toggle::make('knows_html_css_js')
                    ->label('Knows HTML, CSS, and basic JavaScript?')
                    ->required()
                    ->inline(false),

                Select::make('knows_react_next')
                    ->label('Knowledge of React/Next.js')
                    ->options([
                        'none' => 'None',
                        'basic' => 'Basic',
                        'advanced' => 'Advanced',
                    ])
                    ->required(),

                Toggle::make('can_build_crud_with_db')
                    ->label('Can build a CRUD app with a database?')
                    ->required()
                    ->inline(false),

                Toggle::make('can_auth_password_google')
                    ->label('Can implement authentication (password + Google)?')
                    ->required()
                    ->inline(false),

                Select::make('knows_express_hono_or_laravel')
                    ->label('Backend frameworks (Express/Hono/Laravel)')
                    ->options([
                        'none' => 'None',
                        'basic' => 'Basic',
                        'proficient' => 'Proficient',
                    ])
                    ->required(),

                Toggle::make('knows_golang')
                    ->label('Knows Golang?')
                    ->required()
                    ->inline(false),

                // Tier is computed on save; no manual input here.
            ]);
    }
}
