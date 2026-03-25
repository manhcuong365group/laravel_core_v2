<?php

namespace App\Livewire\Backend\Settings;

use App\Actions\Setting\UpdateGeneralSetting;
use App\Models\Setting;
use Livewire\Component;
use Livewire\WithFileUploads;

class GeneralPage extends Component
{
    use WithFileUploads;

    public string $site_name = '';
    public string $site_tagline = '';
    public $site_logo;
    public $site_favicon;
    public string $timezone = 'Asia/Ho_Chi_Minh';
    public string $date_format = 'd/m/Y';
    public bool $maintenance_mode = false;

    public $current_site_logo;
    public $current_site_favicon;

    public function mount(): void
    {
        $settings = Setting::getGroup('general');
        
        $this->site_name = $settings['site_name'] ?? '';
        $this->site_tagline = $settings['site_tagline'] ?? '';
        $this->current_site_logo = $settings['site_logo'] ?? '';
        $this->current_site_favicon = $settings['site_favicon'] ?? '';
        $this->timezone = $settings['timezone'] ?? 'Asia/Ho_Chi_Minh';
        $this->date_format = $settings['date_format'] ?? 'd/m/Y';
        $this->maintenance_mode = ($settings['maintenance_mode'] ?? 'false') === 'true';
    }

    public function update(UpdateGeneralSetting $updateGeneralSetting): void
    {
        $data = [
            'site_name' => $this->site_name,
            'site_tagline' => $this->site_tagline,
            'timezone' => $this->timezone,
            'date_format' => $this->date_format,
            'maintenance_mode' => $this->maintenance_mode ? 'true' : 'false',
        ];

        if ($this->site_logo) {
            $data['site_logo'] = $this->site_logo;
        }

        if ($this->site_favicon) {
            $data['site_favicon'] = $this->site_favicon;
        }

        $updateGeneralSetting->handle($data);

        $this->current_site_logo = Setting::get('site_logo');
        $this->current_site_favicon = Setting::get('site_favicon');
        $this->site_logo = null;
        $this->site_favicon = null;

        $this->dispatch('toast', message: 'Cập nhật cấu hình thành công!', type: 'success');
    }

    public function render()
    {
        return view('livewire.backend.settings.general-page')
            ->layout('backend.layouts.app', ['title' => 'Cấu hình chung']);
    }
}

