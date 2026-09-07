import * as InputmaskModule from "./libs/inputmask.js";

const mod = InputmaskModule.default ?? InputmaskModule;

window.Inputmask = mod && mod.default ? mod.default : mod;
