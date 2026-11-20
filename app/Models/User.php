<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Mail\CodigoVerificacion;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

#[Fillable(['name', 'email', 'password', 'role', 'codigo', 'codigo_expira_at'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
       protected function casts(): array
   {
       return [
           'email_verified_at' => 'datetime',
           'password' => 'hashed',
           'codigo_expira_at' => 'datetime',
       ];
   }
      public function esAdmin(): bool
   {
       return $this->role === 'admin';
   }

   public function enviarCodigo(): void
   {
       $codigo = (string) random_int(100000, 999999);

       $this->update([
           'codigo' => Hash::make($codigo),
           'codigo_expira_at' => now()->addMinutes(5),
       ]);

       Mail::to($this->email)->send(new CodigoVerificacion($codigo));
   }
}
