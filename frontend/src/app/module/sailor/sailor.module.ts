import {CommonModule} from '@angular/common';
import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {ThemeModule} from '@core/theme/theme.module';
import {ReactiveFormsModule} from '@angular/forms';
import {CreateSailorComponent} from './pages/create-sailor/create-sailor.component';
import {DeleteSailorComponent} from './pages/delete-sailor/delete-sailor.component';

const routes: Routes = [
  {
    path: 'create',
    component: CreateSailorComponent,
  },
  {
    path: 'delete',
    component: DeleteSailorComponent,
  },
];

@NgModule({
  declarations: [
    CreateSailorComponent,
    DeleteSailorComponent,
  ],
  imports: [
    CommonModule,
    ThemeModule,
    ReactiveFormsModule,
    RouterModule.forChild(routes),
  ],
})
export class SailorModule { }
