import {
    Component,
    ViewEncapsulation,
    OnInit,
    Inject,
    ViewChild,
} from '@angular/core';
import {
    FormControl,
    FormGroup,
    FormsModule,
    NgForm,
    ReactiveFormsModule,
    UntypedFormBuilder,
    UntypedFormGroup,
    Validators,
} from '@angular/forms';
import { MatButtonModule } from '@angular/material/button';
import {
    MAT_DIALOG_DATA,
    MatDialogModule,
    MatDialogRef,
} from '@angular/material/dialog';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatIconModule } from '@angular/material/icon';
import { MatInputModule } from '@angular/material/input';
import { DatePipe, NgFor, NgIf } from '@angular/common';
import { MatDatepickerModule } from '@angular/material/datepicker';
import { MatCheckboxModule } from '@angular/material/checkbox';
import { MatSelectModule } from '@angular/material/select';
import { Entity } from 'app/_models/entity.model';
import { Regional } from 'app/_models/regional.model';
import { Specialty } from 'app/_models/specialty.model';
import { EntityService } from 'app/_services/entity.services';
import { SpecialtyService } from 'app/_services/specialty.services';
import { RegionalService } from 'app/_services/regional.services';
import { cnpjValidator } from 'app/_helpers/validate-cnpj';
import { specialtiesValidator } from 'app/_helpers/validare-specialtis';
import { forkJoin, finalize } from 'rxjs';
import { AlertaErrorComponent } from 'app/shared/componentes/alerta-error/alerta-error.component';

export interface DialogData {
    entity: Entity;
}

@Component({
    selector: 'entity-store',
    standalone: true,
    providers: [DatePipe],
    templateUrl: './entity-store.component.html',
    encapsulation: ViewEncapsulation.None,
    imports: [
        MatIconModule,
        MatFormFieldModule,
        MatInputModule,
        FormsModule,
        ReactiveFormsModule,
        MatButtonModule,
        MatDialogModule,
        NgIf,
        MatDatepickerModule,
        MatCheckboxModule,
        NgFor,
        MatSelectModule,
        AlertaErrorComponent,
    ],
})
export class EntityStoreComponent implements OnInit {
    @ViewChild('ngForm') ngForm: NgForm;

    alert: { type: string; message: string } = { type: '', message: '' };

    form: UntypedFormGroup;
    showAlert: boolean = false;

    entity!: Entity;
    specialtiesList: Specialty[];
    regioes: Regional[];

    toppings = new FormControl('');
    edit: boolean = false;

    constructor(
        public dialogRef: MatDialogRef<EntityStoreComponent>,
        private _formBuilder: UntypedFormBuilder,
        @Inject(MAT_DIALOG_DATA) public data: DialogData,
        private entityService: EntityService,
        private datePipe: DatePipe,
        private specialtyService: SpecialtyService,
        private regionalService: RegionalService
    ) {}

    ngOnInit(): void {
        this.loadData();

        this.edit = this.data.entity != null;

        this.form = this._formBuilder.group({
            corporate_reason: [''],
            fantasy_name: [''],
            cnpj: [''],
            regionalId: [''],
            specialties: [''],
            opening_date: [''],
            active: ['1'],
        });

        if (this.edit) this.form.patchValue(this.data.entity || {});
    }

    loadData(): void {
        forkJoin([this.specialtyService.read(), this.regionalService.read()])
            .pipe(
                finalize(() => {
                    this.form.enable();
                })
            )
            .subscribe(([specialties, regions]) => {
                this.specialtiesList = specialties;
                this.regioes = regions;
            });
    }

    onSubmit(): void {
        const fields = [
            'corporate_reason',
            'fantasy_name',
            'cnpj',
            'regionalId',
            'opening_date',
            'specialties',
        ];

        fields.forEach((field) => {
            this.form.get(field).setValidators(Validators.required);
            this.form.get(field).updateValueAndValidity();
        });

        this.form.get('cnpj').setValidators(cnpjValidator());
        this.form.get('cnpj').updateValueAndValidity();

        this.form.get('specialties').setValidators(specialtiesValidator());
        this.form.get('specialties').updateValueAndValidity();

        if (this.form.invalid) {
            this.showAlertMessage(
                'error',
                'Dados incorretos. Por favor, revise seus dados e tente novamente'
            );
            return;
        }

        this.showAlert = false;

        const openingDate = this.form.get('opening_date').value;
        const formattedDate = this.datePipe.transform(
            openingDate,
            'yyyy-MM-dd HH:mm:ss'
        );
        this.form.get('opening_date').setValue(formattedDate);

        if (this.edit) {
            this.entityService
                .update(this.form.value, this.data.entity.id)
                .subscribe(
                    (res) => {
                        this.entityService.showMessage(
                            'Entidade editado com sucesso!'
                        );
                        this.dialogRef.close(res);
                    },
                    (error) => {
                        this.showAlertMessage(
                            'error',
                            'Erro ao editar a entidade. Por favor, tente novamente.'
                        );
                    }
                );
        } else {
            this.entityService.create(this.form.value).subscribe(
                (res) => {
                    this.entityService.showMessage(
                        'Entidade criada com sucesso!'
                    );
                    this.dialogRef.close(res);
                },
                (error) => {
                    this.showAlertMessage(
                        'error',
                        'Erro ao criar a entidade. Por favor, tente novamente.'
                    );
                }
            );
        }
    }

    showAlertMessage(type: string, message: string): void {
        this.alert = { type, message };
        this.showAlert = true;
    }

    getSelectedSpecialtiesNames(): string {
        const selectedSpecialties = this.form.get('specialties').value;
        if (selectedSpecialties && selectedSpecialties.length > 0) {
            const firstSpecialty = selectedSpecialties[0].name;
            const additionalCount = selectedSpecialties.length - 1;

            return additionalCount > 0
                ? `${firstSpecialty} (+${additionalCount} ${
                      additionalCount > 1 ? 'especialidades' : 'especialidade'
                  })`
                : firstSpecialty;
        }
        return '';
    }

    delete(): void {
        this.entityService.delete(this.data.entity.id).subscribe(
            (res) => {
                this.entityService.showMessage(
                    'Entidade deletada com sucesso!'
                );
                this.dialogRef.close('success');
            },
            (error) => {
                this.showAlertMessage(
                    'error',
                    'Erro ao deletear a entidade. Por favor, tente novamente.'
                );
            }
        );
    }

    seletedSpecialties(o1: any, o2: any) {
        return o1.name == o2.name && o1.id == o2.id;
    }
}
