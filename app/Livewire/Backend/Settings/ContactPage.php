<?php

namespace App\Livewire\Backend\Settings;

use App\Actions\Setting\UpdateContactSetting;
use App\Models\Setting;
use Livewire\Component;

class ContactPage extends Component
{
    public string $company_name = '';
    public string $address = '';
    public string $working_hours = '';
    public string $phone = '';
    public string $hotline = '';
    public string $email = '';
    public string $google_maps_embed = '';
    public string $google_maps_link = '';

    public function mount(): void
    {
        $settings = Setting::getGroup('contact');
        
        $this->company_name = $settings['company_name'] ?? '';
        $this->address = $settings['address'] ?? '';
        $this->working_hours = $settings['working_hours'] ?? '';
        $this->phone = $settings['phone'] ?? '';
        $this->hotline = $settings['hotline'] ?? '';
        $this->email = $settings['email'] ?? '';
        $this->google_maps_embed = $settings['google_maps_embed'] ?? '';
        $this->google_maps_link = $settings['google_maps_link'] ?? '';
    }

    public function update(UpdateContactSetting $updateContactSetting): void
    {
        $data = [
            'company_name' => $this->company_name,
            'address' => $this->address,
            'working_hours' => $this->working_hours,
            'phone' => $this->phone,
            'hotline' => $this->hotline,
            'email' => $this->email,
            'google_maps_embed' => $this->google_maps_embed,
            'google_maps_link' => $this->google_maps_link,
        ];

        $updateContactSetting->handle($data);

        $this->dispatch('toast', message: 'Cập nhật thông tin liên hệ thành công!', type: 'success');
    }

    public function render()
    {
        return view('livewire.backend.settings.contact-page')
            ->layout('backend.layouts.app', ['title' => 'Thông tin liên hệ']);
    }
}

