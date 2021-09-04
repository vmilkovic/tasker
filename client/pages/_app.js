import { Provider } from "next-auth/client";

import "../styles/globals.scss";

function Todoer({ Component, pageProps }) {
  return (
    <Provider
      session={pageProps.session}
      options={{
        clientMaxAge: 60,
        keepAlive: 5 * 60,
      }}
    >
      <Component {...pageProps} />
    </Provider>
  );
}

export default Todoer;
