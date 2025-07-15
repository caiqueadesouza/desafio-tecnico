import { Routes } from '@angular/router';
import { EntityReadComponent } from './entity-read/entity-read.component';

const routes: Routes = [
    {
        path: '',
        component: EntityReadComponent,
        pathMatch: 'full',
    },
];

export default routes;
