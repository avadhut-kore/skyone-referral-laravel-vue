import { Validator } from 'vee-validate';
import { apiHost, getHeader, providehelpconditions, withdrawconditions} from'./config';

//console.log('custom');
Validator.extend('providehelp_multiple_of', {
  getMessage: field => {
    return 'Amount should be multiple of '+providehelpconditions.multiple;
  },
  // Returns a boolean value
  validate: value => {
    return value % providehelpconditions.multiple == 0;
  }
});

//console.log('custom');
Validator.extend('withdraw_multiple_of', {
  getMessage: field => {
    return 'Amount should be multiple of '+withdrawconditions.multiple;
  },
  // Returns a boolean value
  validate: value => {
    return value % withdrawconditions.multiple == 0;
  }
});

Validator.extend('withdraw_min', {
  getMessage: field => {
    return 'Amount should be min  '+withdrawconditions.min;
  },
  // Returns a boolean values
  validate: value => {
    return value <= withdrawconditions.min;
  }
});

Validator.extend('withdraw_max', {
  getMessage: field => {
    return 'Amount should be max  '+withdrawconditions.max;
  },
  // Returns a boolean value
  validate: value => {
    return value >= withdrawconditions.max;
  }
});