/**
 *
 * Form
 *
 */

import {setInputValid, setInputInvalid, validateInput} from "./input-validator.js"

// const buttonClasses = {
//   disabled: "button--disabled",
// };
// function disableButton(button) {
//   if (!button.innerText) return;
//   button.classList.add(buttonClasses.disabled);
//   button.disabled = true;
// }
// function enableButton(button) {
//   if (!button.innerText) return;
//   button.classList.remove(buttonClasses.disabled);
//   button.disabled = false;
// }


function serealizeForm(formNode) {
  const inputs = [...formNode.querySelectorAll('input')];
  const selects = [...formNode.querySelectorAll('select')];
  const textareas = [...formNode.querySelectorAll('textarea')];

  let formData = {};
   // Обработка полей input
  for (let i = 0; i < inputs.length; i++) {
    const input = inputs[i];
    if (input.name === '') continue;

    if (input.type === 'checkbox') {
      if (!Array.isArray(formData[input.name])) {
        formData[input.name] = [];
      }
      if (!input.checked) continue;

      formData[input.name].push(input.value)
      continue;
    }

    formData[input.name] = input.value;
  }

  // Обработка полей select
  for (let i = 0; i < selects.length; i++) {
    const select = selects[i];
    formData[select.name] = select.value;
  }

  // Обработка полей textarea
  for (let i = 0; i < textareas.length; i++) {
    const textarea = textareas[i];
    formData[textarea.name] = textarea.value;
  }

  /*
  for (const key in formData) {
    if (Object.prototype.hasOwnProperty.call(formData, key)) {
      // Проверяем, является ли значение свойства массивом
      if (Array.isArray(formData[key])) {
        // Преобразуем массив в JSON-строку и присваиваем свойству
        formData[key] = JSON.stringify(formData[key]);
      }
    }
  }
   */

  return formData;

}

function getFormData(serealizedForm) {
  const formData = new FormData();
    for (const key in serealizedForm) {
      if (serealizedForm.hasOwnProperty(key)) {
        formData.append(key, serealizedForm[key]);
      }
    }

  return formData;
}

const formsList = document.querySelectorAll(".js_form");
formsList.forEach((form) => {
  const button = form.querySelector('.js_form__submit')
  if (button) {
    button.addEventListener('click', () => {
      const submit =  new Event('submit', { bubbles: true, cancelable: false });
      form.dispatchEvent(submit);
    })
  }

  form._formich = {};
  form._formich.serealize = () => serealizeForm(form);
  form._formich.getFormData = () => getFormData(serealizeForm(form));
  form._formich.checkValidity = (highlightInvalid = true) => {
    const inputsToValidate = [
      ...form.querySelectorAll('.js_form__control')
    ];

    inputsToValidate.forEach((input) => {
      validateInput(input, highlightInvalid);
    });

    if (form.querySelector('.is-invalid')) {
      if (highlightInvalid !== true) return false;
      window.scroll({
        top: form.querySelector('.is-invalid').getBoundingClientRect().top + pageYOffset,
        left: 0,
        behavior: 'smooth'
      })
      return false;
    }

    return true;
  }
  form._formich.disableSubmit = (callback = () => {}) => {
    const submitButton = form.querySelector('.js_form__submit');
    submitButton.classList.add('button--wait');

    if (typeof callback === 'function') {
      callback()
    }
  }
  form._formich.enableSubmit = (callback = () => {}) => {
    const submitButton = form.querySelector('.js_form__submit');
    submitButton.classList.remove('button--wait');

    if (typeof callback === 'function') {
      callback()
    }
  }

  form.addEventListener("submit", async (event) => {
    event.preventDefault();

    if (!form._formich.checkValidity(true)) return;

    extractUTM(form);

    const serealizedForm = serealizeForm(form);
    const formData = getFormData(serealizedForm);

    if (form.dataset.action) {
      formData.append('action', form.dataset.action);
    }

    let response = await fetch(form.dataset.route, {
      method: "POST",
      body: formData,
    });

    if (!form.classList.contains('js_form--no-lock-button')) {
      form._formich.disableSubmit();
    }

    try {
      let result = await response.text();

      const submitButton = form.querySelector('.js_form__submit');
      let buttonText;
      let buttonTextElement;
      let submitButtonText = submitButton.querySelector('.button__text');

      if (submitButtonText) {
        buttonTextElement = submitButtonText;
      } else {
        buttonTextElement = submitButton;
      }
      buttonText = buttonTextElement.innerText;

      if (form.classList.contains('js_form--no-lock-button')) {
        if(submitButton.dataset.textSuccess) {
          buttonTextElement.innerText = submitButton.dataset.textSuccess;
          // buttonTextElement.innerText = '✓ Ваша заявка принята';
        }
      }

      if (!form.classList.contains('js_form--no-reset')) {
        resetForm(form)
      }

      const submitSuccess = new CustomEvent('submit-success', {
        detail: {
          form,
          result,
        },
        bubbles: true, // Allow event to bubble up the DOM tree
        cancelable: true // Allow event to be canceled
      });
      form.dispatchEvent(submitSuccess);

      if (!form.classList.contains('js_form--no-lock-button')) {
        setTimeout(() => {
          form._formich.enableSubmit();
          buttonTextElement.innerText = buttonText;
        }, 5000)
      }

    } catch(error) {
      console.error(error)
    }

  });

  form.addEventListener("submit-success", async (event) => {})
});

function resetForm (form) {
  form.querySelectorAll('input').forEach(input => {
    const INPUTS_TO_IGNORE_RESET = [
      'radio',
      'checkbox',
      'hidden',
    ]
    if (INPUTS_TO_IGNORE_RESET.includes(input.type)) return;
    if (input.getAttribute('readonly') != null) return;
    if (input.getAttribute('hidden') != null) return;

    input.value = ''
  })
}

function extractUTM(form) {
  const urlParams = new URLSearchParams(window.location.search);

  // Запись значений UTM-меток в соответствующие поля формы
  const utmSource = form.querySelector('input[name="utm_source"]');
  if (utmSource) {
    utmSource.value = urlParams.get('utm_source') || '';
  }

  const utmMedium = form.querySelector('input[name="utm_medium"]');
  if (utmMedium) {
    utmMedium.value = urlParams.get('utm_medium') || '';
  }

  const utmCampaign = form.querySelector('input[name="utm_campaign"]');
  if (utmCampaign) {
    utmCampaign.value = urlParams.get('utm_campaign') || '';
  }

  const utmContent = form.querySelector('input[name="utm_content"]');
  if (utmContent) {
    utmContent.value = urlParams.get('utm_content') || '';
  }

  const utmTerm = form.querySelector('input[name="utm_term"]')
  if (utmTerm) {
    utmTerm.value = urlParams.get('utm_term') || '';
  }

  // Запись значения referer в соответствующее поле формы
  const referrer = form.querySelector('input[name="referrer"]')
  if (referrer) {
    referrer.value = document.referrer || '';
  }

  // Запись времени отправки формы в соответствующее поле
  const requestTime = form.querySelector('input[name="requestTime"]')
  if (requestTime) {
    requestTime.value = Date.now();
  }

  // Запись простой подписи (например, md5 хэш) для защиты от подделки данных на клиенте
  // document.querySelector('input[name="requestSimpleSign"]').value = 'Ваша простая подпись';
}

// #region input-labels
const inputs = document.querySelectorAll(".js_form .js_form__control");
inputs.forEach(input => {
  input._formich = {};
  input._formich.validate = () => {
    validateInput(input);
  }
})

const inputClasses = {
  invalid: "is-invalid",
  init: "input--init",
  active: "input--active",
  dropdown: "select",
  activeDropdown: "select--active",
  selectedDropdown: "input--selected-dropdown",
};

function activateInput(input) {
//   input.classList.add(inputClasses.active);
}
function deactivateInput(input) {
//   input.classList.remove(inputClasses.active);
}

function initInputs(inputs) {
  inputs.forEach((input) => {
    if (input.classList.contains(inputClasses.init)) return;
    input.classList.add(inputClasses.init);

    // input._formich.validate = () => {
    //   validateInput(input);
    // }

    const field = input.querySelector("[required]");

    input.addEventListener("click", (e) => {
      if (!e.target.classList.contains("select__option") && !e.target.classList.contains("select__toggle")) return;

      setTimeout(() => {
        if (e.target.classList.contains('select__option--active')) {
          setInputValid(input);
        }
      },300)
    });

    if (!field) return;

    field.addEventListener("focus", () => {
      activateInput(input);
      // setInputValid(input);
    });
    field.addEventListener("blur", () => {
      deactivateInput(input);
      if (field.value != "") {
        validateInput(input);
      }
    });

    field.addEventListener('input', () => {
      setInputValid(input);

      if (field.type != 'tel') return;

      field.value = field.value.replace(/[^\d-+()]/g, '');
    });
    field.addEventListener('change', () => {
      setInputValid(input);
    })

    if (field.type != "email" && field.type != "tel") {
      field.addEventListener("input", (e) => {
        validateInput(input);
      });
    }

    if (field.value !== "") {
      input.classList.add(inputClasses.active);
    }
  });
}

initInputs(inputs);

// #endregion input-labels


/*

     window.addEventListener('load', function(){
        return;
        let loc = document.getElementById("855929640f27f2de67f");
        loc.value = window.location.href;
        let ref = document.getElementById("855929640f27f2de67fref");
        ref.value = document.referrer;

        let statUrl = "https://academychiptuning.by/stat/counter?ref=" + encodeURIComponent(document.referrer)
            + "&loc=" + encodeURIComponent(document.location.href);
        document.getElementById('gccounterImgContainer').innerHTML
            = "<img width=1 height=1 style='display:none' id='gccounterImg' src='" + statUrl + "'/>";
    });

*/
