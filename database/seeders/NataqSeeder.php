<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Equipment;
use App\Models\Measurement;
use App\Models\Room;
use Illuminate\Database\Seeder;

class NataqSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::query()->updateOrCreate(
            ['name' => 'Nataq'],
            [
                'responsible_name' => 'Murillo Gamba',
                'responsible_phone' => null,
                'address' => 'Rua Nestor Guimarães, 111, Sala 21 – Bairro Estrela – Ponta Grossa PR – CEP 84040-130',
                'layout_description' => 'Possui duas salas, uma cozinha e dois banheiros. O ponto de acesso fica na sala 01.',
                'notebooks_count' => 10,
                'smartphones_count' => 12,
                'cameras_count' => 1,
                'other_devices_count' => 1,
                'notes' => '1 fechadura digital. Total atual: 24 dispositivos conectados simultaneamente.',
            ]
        );

        Equipment::query()->updateOrCreate(
            ['company_id' => $company->id],
            [
                'brand' => 'DZS',
                'model' => 'ZNID-GPON-2428B1',
                'summary' => 'Terminal de rede óptica indoor do tipo ONT (Optical Network Terminal) com função de roteador de alta performance, desenvolvido para redes de fibra óptica residenciais e corporativas (FTTH). Reúne conversor de fibra, portas LAN, Wi-Fi integrado e saídas VOIP. Wi-Fi Dual band Wi-Fi 5 (802.11ac) com 5 antenas internas.',
                'technical_specs' => "Uplink Óptico (PON): 1x Porta GPON SC/APC (Download até 2.5 Gbps / Upload até 1.25 Gbps).\nPortas Ethernet: 4x Portas Gigabit Ethernet (10/100/1000 Base-T RJ-45).\nTelefonia (VoIP): 2x Portas FXS (RJ-11) com suporte a SIP e MGCP.\nPorta USB: 1x USB 2.0 (suporta modem/dongle 3G/4G para backup).\nAlimentação: Fonte externa 12V DC / 2A (Entrada 100-240V AC).\nModos de Operação: Router (NAT/PPPoE/DHCP) e Bridge.\nSuporte a VLANs: 802.1Q, QinQ, mapeamento de VLAN por SSID ou porta física.\nGerenciamento Remoto: OMCI, TR-069, SNMP e Interface Web local.",
                'wifi_specs' => "Wi-Fi Dual-Band (Wi-Fi 5 / 802.11ac).\n2.4 GHz: 802.11b/g/n, MIMO 2x2 (até 300 Mbps), 2 antenas internas (+3 dBi), canais 20/40 MHz.\n5.0 GHz: 802.11a/n/ac, MIMO 3x3 (até 1300 Mbps), 3 antenas internas (+4 dBi), canais 20/40/80 MHz.\nMIMO: Multiple Input Multiple Output – 2x2 em 2.4 GHz e 3x3 em 5.0 GHz (5 antenas no total).",
                'security_specs' => "Autenticação: WPA-PSK, WPA2-PSK (padrão de fábrica) e 802.1X/RADIUS.\nCriptografia: AES, TKIP e WEP (64/128-bit).\nWPS via botão físico ou PIN.\nAté 4 SSIDs independentes por frequência.\nFirewall nativo e filtragem de MAC.",
                'max_wifi_devices' => 128,
                'recommended_devices' => 25,
            ]
        );

        $roomNames = [
            'Cozinha',
            'Banheiro 02',
            'Sala de Reuniões',
            'Banheiro 01',
            'Escritório',
        ];

        $rooms = [];
        foreach ($roomNames as $index => $name) {
            $rooms[$name] = Room::query()->updateOrCreate(
                ['company_id' => $company->id, 'name' => $name],
                ['sort_order' => $index + 1]
            );
        }

        $datasets = [
            '2026-09-04' => [
                '08h' => [
                    'Cozinha' => [61.8, 306.9],
                    'Banheiro 02' => [28.8, 505.8],
                    'Sala de Reuniões' => [33.6, 591.0],
                    'Banheiro 01' => [71.5, 627.6],
                    'Escritório' => [40.8, 668.2],
                ],
                '13h' => [
                    'Cozinha' => [45.6, 580.0],
                    'Banheiro 02' => [31.0, 544.3],
                    'Sala de Reuniões' => [28.1, 649.1],
                    'Banheiro 01' => [49.4, 610.4],
                    'Escritório' => [27.2, 610.8],
                ],
                '17h' => [
                    'Cozinha' => [58.8, 421.9],
                    'Banheiro 02' => [36.1, 515.6],
                    'Sala de Reuniões' => [29.7, 626.3],
                    'Banheiro 01' => [69.4, 620.8],
                    'Escritório' => [37.2, 651.2],
                ],
            ],
            '2026-09-08' => [
                '08h' => [
                    'Cozinha' => [54.0, 462.7],
                    'Banheiro 02' => [29.0, 512.4],
                    'Sala de Reuniões' => [31.9, 639.4],
                    'Banheiro 01' => [56.2, 625.8],
                    'Escritório' => [37.0, 621.7],
                ],
                '13h' => [
                    'Cozinha' => [54.6, 403.1],
                    'Banheiro 02' => [30.1, 536.0],
                    'Sala de Reuniões' => [33.4, 604.5],
                    'Banheiro 01' => [51.2, 620.8],
                    'Escritório' => [37.1, 626.7],
                ],
                '17h' => [
                    'Cozinha' => [56.7, 448.3],
                    'Banheiro 02' => [29.2, 511.1],
                    'Sala de Reuniões' => [29.1, 648.8],
                    'Banheiro 01' => [60.9, 620.4],
                    'Escritório' => [37.2, 641.9],
                ],
            ],
        ];

        foreach ($datasets as $date => $slots) {
            foreach ($slots as $timeSlot => $roomSpeeds) {
                foreach ($roomSpeeds as $roomName => [$speed24, $speed5]) {
                    Measurement::query()->updateOrCreate(
                        [
                            'room_id' => $rooms[$roomName]->id,
                            'measured_on' => $date,
                            'time_slot' => $timeSlot,
                        ],
                        [
                            'company_id' => $company->id,
                            'speed_24_mbps' => $speed24,
                            'speed_5_mbps' => $speed5,
                        ]
                    );
                }
            }
        }
    }
}
