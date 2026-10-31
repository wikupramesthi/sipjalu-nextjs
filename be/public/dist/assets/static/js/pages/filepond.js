
FilePond.registerPlugin(
  FilePondPluginImagePreview,
  FilePondPluginImageCrop,
  FilePondPluginImageExifOrientation,
  FilePondPluginImageFilter,
  FilePondPluginImageResize,
  FilePondPluginFileValidateSize,
  FilePondPluginFileValidateType,
)

// Filepond: Basic
FilePond.create(document.querySelector(".basic-filepond"), {
  credits: null,
  allowImagePreview: false,
  allowMultiple: false,
  allowFileEncode: false,
  required: false,
  storeAsFile: true,
})

// Filepond: Multiple Files
FilePond.create(document.querySelector(".multiple-files-filepond"), {
  credits: null,
  allowImagePreview: false,
  allowMultiple: true,
  allowFileEncode: false,
  required: false,
  storeAsFile: true,
})

// Filepond: With Validation
FilePond.create(document.querySelector(".with-validation-filepond"), {
  credits: null,
  allowImagePreview: false,
  allowMultiple: true,
  allowFileEncode: false,
  required: true,
  acceptedFileTypes: ["image/png"],
  fileValidateTypeDetectType: (source, type) =>
    new Promise((resolve, reject) => {
      // Do custom type detection here and return with promise
      resolve(type)
    }),
  storeAsFile: true,
})

// Filepond: Image Preview
FilePond.create(document.querySelector(".image-preview-filepond"), {
  credits: null,
  allowImagePreview: true,
  allowImageFilter: false,
  allowImageExifOrientation: false,
  allowImageCrop: false,
  acceptedFileTypes: ["image/png", "image/jpg", "image/jpeg"],
  fileValidateTypeDetectType: (source, type) =>
    new Promise((resolve, reject) => {
      // Do custom type detection here and return with promise
      resolve(type)
    }),
  storeAsFile: true,
})

// Filepond: Image Crop
FilePond.create(document.querySelector(".image-crop-filepond"), {
  credits: null,
  allowImagePreview: true,
  allowImageFilter: false,
  allowImageExifOrientation: false,
  allowImageCrop: true,
  acceptedFileTypes: ["image/png", "image/jpg", "image/jpeg"],
  fileValidateTypeDetectType: (source, type) =>
    new Promise((resolve, reject) => {
      // Do custom type detection here and return with promise
      resolve(type)
    }),
  storeAsFile: true,
})

// Filepond: Image Exif Orientation
FilePond.create(document.querySelector(".image-exif-filepond"), {
  credits: null,
  allowImagePreview: true,
  allowImageFilter: false,
  allowImageExifOrientation: true,
  allowImageCrop: false,
  acceptedFileTypes: ["image/png", "image/jpg", "image/jpeg"],
  fileValidateTypeDetectType: (source, type) =>
    new Promise((resolve, reject) => {
      // Do custom type detection here and return with promise
      resolve(type)
    }),
  storeAsFile: true,
})

// Filepond: Image Filter
FilePond.create(document.querySelector(".image-filter-filepond"), {
  credits: null,
  allowImagePreview: true,
  allowImageFilter: true,
  allowImageExifOrientation: false,
  allowImageCrop: false,
  imageFilterColorMatrix: [
    0.299, 0.587, 0.114, 0, 0, 0.299, 0.587, 0.114, 0, 0, 0.299, 0.587, 0.114,
    0, 0, 0.0, 0.0, 0.0, 1, 0,
  ],
  acceptedFileTypes: ["image/png", "image/jpg", "image/jpeg"],
  fileValidateTypeDetectType: (source, type) =>
    new Promise((resolve, reject) => {
      // Do custom type detection here and return with promise
      resolve(type)
    }),
  storeAsFile: true,
})

// Filepond: Image Resize
FilePond.create(document.querySelector(".image-resize-filepond"), {
  credits: null,
  allowImagePreview: true,
  allowImageFilter: false,
  allowImageExifOrientation: false,
  allowImageCrop: false,
  allowImageResize: true,
  imageResizeTargetWidth: 200,
  imageResizeTargetHeight: 200,
  imageResizeMode: "cover",
  imageResizeUpscale: true,
  acceptedFileTypes: ["image/png", "image/jpg", "image/jpeg"],
  fileValidateTypeDetectType: (source, type) =>
    new Promise((resolve, reject) => {
      // Do custom type detection here and return with promise
      resolve(type)
    }),
  storeAsFile: true,
})
