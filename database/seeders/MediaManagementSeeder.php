<?php

namespace Database\Seeders;

use App\Models\BusinessPlanDocument;
use App\Models\PromotionBanner;
use Illuminate\Database\Seeder;

class MediaManagementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed 10 existing banners if empty
        if (PromotionBanner::count() === 0) {
            $banners = [
                ['id' => 1, 'image_path' => 'b1.jpeg', 'title' => 'Official Promo Banner #1', 'tag' => 'SYNC TRADE Ecosystem', 'sort_order' => 1],
                ['id' => 2, 'image_path' => 'b2.jpeg', 'title' => 'Official Promo Banner #2', 'tag' => 'Staking & High Yield', 'sort_order' => 2],
                ['id' => 3, 'image_path' => 'b3.jpeg', 'title' => 'Official Promo Banner #3', 'tag' => 'Global Community', 'sort_order' => 3],
                ['id' => 4, 'image_path' => 'b4.jpeg', 'title' => 'Official Promo Banner #4', 'tag' => 'Partnership Program', 'sort_order' => 4],
                ['id' => 5, 'image_path' => 'b5.jpeg', 'title' => 'Official Promo Banner #5', 'tag' => 'Crypto Growth', 'sort_order' => 5],
                ['id' => 6, 'image_path' => 'b6.jpeg', 'title' => 'Official Promo Banner #6', 'tag' => 'Secure Blockchain', 'sort_order' => 6],
                ['id' => 7, 'image_path' => 'b7.jpeg', 'title' => 'Official Promo Banner #7', 'tag' => 'Referral Commission', 'sort_order' => 7],
                ['id' => 8, 'image_path' => 'b8.jpeg', 'title' => 'Official Promo Banner #8', 'tag' => 'Passive Income', 'sort_order' => 8],
                ['id' => 9, 'image_path' => 'b9.jpeg', 'title' => 'Official Promo Banner #9', 'tag' => 'Network Expansion', 'sort_order' => 9],
                ['id' => 10, 'image_path' => 'b10.jpeg', 'title' => 'Official Promo Banner #10', 'tag' => 'Token Rewards', 'sort_order' => 10],
            ];

            foreach ($banners as $b) {
                PromotionBanner::create([
                    'title' => $b['title'],
                    'tag' => $b['tag'],
                    'image_path' => $b['image_path'],
                    'sort_order' => $b['sort_order'],
                    'status' => 'active',
                ]);
            }
        }

        // 2. Seed 3 existing PDFs if empty
        if (BusinessPlanDocument::count() === 0) {
            $pdfs = [
                [
                    'title' => 'SYNC TRADE Global Business Plan',
                    'language' => 'English',
                    'native_language' => 'English',
                    'flag' => '🇬🇧',
                    'flag_code' => 'gb',
                    'badge' => 'Global Edition',
                    'badge_color' => '#3b82f6',
                    'gradient' => 'linear-gradient(135deg, rgba(59, 130, 246, 0.2) 0%, rgba(37, 99, 235, 0.05) 100%)',
                    'border_color' => 'rgba(59, 130, 246, 0.3)',
                    'icon_color' => '#60a5fa',
                    'file_path' => 'SYNC TRADE English.pdf',
                    'file_size' => '5.80 MB',
                    'pages_hint' => 'Full Pitch Deck',
                    'description' => 'Complete international presentation detailing the SYNC TRADE ecosystem, Level Income on Activation, Level Income on Staking, Single Leg Income from 15 Stages, Team Withdrawal Commission, Partnership Income, Promotion Airdrop etc.',
                    'sort_order' => 1,
                    'status' => 'active',
                ],
                [
                    'title' => 'SYNC TRADE 中文商业计划书',
                    'language' => 'Chinese',
                    'native_language' => '中文 (简体)',
                    'flag' => '🇨🇳',
                    'flag_code' => 'cn',
                    'badge' => '亚洲官方版 (Asia)',
                    'badge_color' => '#ef4444',
                    'gradient' => 'linear-gradient(135deg, rgba(239, 68, 68, 0.2) 0%, rgba(220, 38, 38, 0.05) 100%)',
                    'border_color' => 'rgba(239, 68, 68, 0.3)',
                    'icon_color' => '#f87171',
                    'file_path' => 'SYNC TRADE Chinese.pdf',
                    'file_size' => '5.96 MB',
                    'pages_hint' => '中文完整版',
                    'description' => '专为华语与亚洲市场打造的完整商业计划书，涵盖账户激活层级收益、质押层级收益、15个阶段单线收益、团队提现佣金、合伙人分红收益及推广空投奖励等。',
                    'sort_order' => 2,
                    'status' => 'active',
                ],
                [
                    'title' => 'SYNC TRADE Презентация на Русском',
                    'language' => 'Russian',
                    'native_language' => 'Русский',
                    'flag' => '🇷🇺',
                    'flag_code' => 'ru',
                    'badge' => 'СНГ / CIS Edition',
                    'badge_color' => '#10b981',
                    'gradient' => 'linear-gradient(135deg, rgba(16, 185, 129, 0.2) 0%, rgba(5, 150, 105, 0.05) 100%)',
                    'border_color' => 'rgba(16, 185, 129, 0.3)',
                    'icon_color' => '#34d399',
                    'file_path' => 'SYNC TRADE Russian.pdf',
                    'file_size' => '5.62 MB',
                    'pages_hint' => 'Полная презентация',
                    'description' => 'Официальный бизнес-план экосистемы SYNC TRADE для русскоязычного сообщества: уровневый доход от активации, уровневый доход от стейкинга, доход по одной линии из 15 этапов, комиссионные от вывода средств команды, партнерский доход, промо-аирдроп и др.',
                    'sort_order' => 3,
                    'status' => 'active',
                ],
            ];

            foreach ($pdfs as $pdf) {
                BusinessPlanDocument::create($pdf);
            }
        }
    }
}
