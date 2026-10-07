<?php

namespace App\Providers;

use App\Models\AdminAccount;
use App\Models\AgendaKegiatan;
use App\Models\AgendaMedia;
use App\Models\Desa;
use App\Models\Dusun;
use App\Models\Fasilitas;
use App\Models\KategoriFasilitas;
use App\Models\KontakPelayanan;
use App\Models\Pengumuman;
use App\Models\ProdukUmkm;
use App\Models\Umkm;
use App\Policies\AdminAccountPolicy;
use App\Policies\AgendaKegiatanPolicy;
use App\Policies\AgendaMediaPolicy;
use App\Policies\DesaPolicy;
use App\Policies\DusunPolicy;
use App\Policies\FasilitasPolicy;
use App\Policies\KategoriFasilitasPolicy;
use App\Policies\KontakPelayananPolicy;
use App\Policies\PengumumanPolicy;
use App\Policies\ProdukUmkmPolicy;
use App\Policies\UmkmPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Desa::class, DesaPolicy::class);
        Gate::policy(Dusun::class, DusunPolicy::class);
        Gate::policy(AdminAccount::class, AdminAccountPolicy::class);
        Gate::policy(KontakPelayanan::class, KontakPelayananPolicy::class);
        Gate::policy(Umkm::class, UmkmPolicy::class);
        Gate::policy(ProdukUmkm::class, ProdukUmkmPolicy::class);
        Gate::policy(KategoriFasilitas::class, KategoriFasilitasPolicy::class);
        Gate::policy(Fasilitas::class, FasilitasPolicy::class);
        Gate::policy(AgendaKegiatan::class, AgendaKegiatanPolicy::class);
        Gate::policy(AgendaMedia::class, AgendaMediaPolicy::class);
        Gate::policy(Pengumuman::class, PengumumanPolicy::class);

        // DEV-05: share $desa with the public layout only (single query per render)
        View::composer('layouts.public', function ($view) {
            $view->with('desa', Desa::query()->first());
        });
    }
}
