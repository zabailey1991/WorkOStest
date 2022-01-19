import React from 'react'
import Document, { Html, Head, Main, NextScript } from 'next/document'

export default class extends Document {
  render() {
    return (
      <Html>
        <Head>
          <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
        </Head>
        <Main />
        <NextScript />
      </Html>
    )
  }
}
