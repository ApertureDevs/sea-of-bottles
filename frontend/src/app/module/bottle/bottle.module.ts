import {CommonModule} from '@angular/common';
import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {ThemeModule} from '@core/theme/theme.module';
import {ReactiveFormsModule} from '@angular/forms';
import {CreateBottleComponent} from './pages/create-bottle/create-bottle.component';

const routes: Routes = [
  {
    path: 'create',
    component: CreateBottleComponent,
  },
];

@NgModule({
  declarations: [CreateBottleComponent],
  imports: [
    CommonModule,
    ThemeModule,
    ReactiveFormsModule,
    RouterModule.forChild(routes),
  ],
})
export class BottleModule { }
