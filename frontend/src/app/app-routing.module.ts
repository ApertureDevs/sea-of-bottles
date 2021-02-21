import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';

const routes: Routes = [
  {
    path: 'landing',
    loadChildren: () => import('./module/landing/landing.module').then((module) => module.LandingModule),
  },
  {
    path: 'bottle',
    loadChildren: () => import('./module/bottle/bottle.module').then((module) => module.BottleModule),
  },
  {
    path: 'sailor',
    loadChildren: () => import('./module/sailor/sailor.module').then((module) => module.SailorModule),
  },
  {
    path: '**',
    redirectTo: 'landing',
  },
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule],
})
export class AppRoutingModule { }
