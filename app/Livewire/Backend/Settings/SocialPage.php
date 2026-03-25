<?php

namespace App\Livewire\Backend\Settings;

use App\Actions\Setting\UpdateSocialSetting;
use App\Models\Setting;
use Livewire\Component;

class SocialPage extends Component
{
    public string $facebook = '';
    public string $youtube = '';
    public string $instagram = '';
    public string $twitter = '';
    public string $linkedin = '';
    public string $tiktok = '';
    public string $zalo = '';

    public function mount(): void
    {
        $settings = Setting::getGroup('social');
        
        $this->facebook = $settings['facebook'] ?? '';
        $this->youtube = $settings['youtube'] ?? '';
        $this->instagram = $settings['instagram'] ?? '';
        $this->twitter = $settings['twitter'] ?? '';
        $this->linkedin = $settings['linkedin'] ?? '';
        $this->tiktok = $settings['tiktok'] ?? '';
        $this->zalo = $settings['zalo'] ?? '';
    }

    public function update(UpdateSocialSetting $updateSocialSetting): void
    {
        $data = [
            'facebook' => $this->facebook,
            'youtube' => $this->youtube,
            'instagram' => $this->instagram,
            'twitter' => $this->twitter,
            'linkedin' => $this->linkedin,
            'tiktok' => $this->tiktok,
            'zalo' => $this->zalo,
        ];

        $updateSocialSetting->handle($data);

        $this->dispatch('toast', message: 'Cập nhật mạng xã hội thành công!', type: 'success');
    }

    public function render()
    {
        return view('livewire.backend.settings.social-page')
            ->layout('backend.layouts.app', ['title' => 'Mạng xã hội']);
    }
}

