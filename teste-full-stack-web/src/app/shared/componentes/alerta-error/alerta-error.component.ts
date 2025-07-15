import { Component, Input } from '@angular/core';
import { MatIconModule } from '@angular/material/icon';

@Component({
    selector: 'app-alerta-error',
    templateUrl: './alerta-error.component.html',
    standalone: true,
    imports: [MatIconModule],
})
export class AlertaErrorComponent {
    @Input() alert!: string;

    constructor() {}
}
